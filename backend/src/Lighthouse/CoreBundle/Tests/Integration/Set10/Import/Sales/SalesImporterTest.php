<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Sales;

use Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Symfony\Component\Console\Output\OutputInterface;

class SalesImporterTest extends WebTestCase
{
    public function testProductInventoryChangedAfterImport()
    {
        $storeId = $this->factory->store()->getStoreId('197');

        $skuAmounts = array(
            '10001' => '-112',
            '10002' => '-10',
            '10003' => '-1',
            '10004' => '-1',
            '10005' => '0.008',
            '10006' => '0',
            '10007' => '-155',
            '10008' => '-1',
            '10009' => '-1',
            '10010' => '-1',
        );
        $productIds = $this->createProductsByNames(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('purchases-14-05-2012_9-18-29.xml', $output);

        $this->assertStringStartsWith('....................', $output->getDisplay());

        foreach ($skuAmounts as $sku => $inventory) {
            $this->assertStoreProduct(
                $storeId,
                $productIds[$sku],
                array('inventory' => $inventory),
                sprintf('Product #%s inventory assertion failed', $sku)
            );
        }
    }

    public function testImportWithNotFoundProducts()
    {
        $this->factory->store()->getStoreId('197');
        $this->createProductsByNames(
            array(
                '10001',
                '10002',
                '10003',
                '10004',
                '10005',
                '10006',
                '10007',
                '10008',
                '10009'
            )
        );

        $output = new TestOutput();
        $this->import('purchases-not-found.xml', $output, 6);

        $lines = $output->getLines();
        $this->assertCount(13, $lines);
        $this->assertEquals("..E......                                            9", $lines[0]);
        $this->assertEquals("......                                               15", $lines[2]);
        $this->assertEquals("...E...                                              22", $lines[4]);
        $this->assertEquals("..                                                   24", $lines[6]);
        $this->assertContains('Errors', $lines[9]);
        $this->assertContains('Product with sku "2873168" not found', $lines[10]);
        $this->assertContains('Product with sku "2873168" not found', $lines[11]);
    }

    public function testImportWithNotFoundShops()
    {
        $this->factory->store()->getStoreId('777');
        $this->createProductsByNames(
            array(
                'Кит-Кат-343424',
                'Мит-Мат',
            )
        );

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".E..                                                 4\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[3]);
        $this->assertContains('Store with number "666" not found', $lines[4]);
    }

    public function testImportDoubleSales()
    {
        $storeIds = $this->factory->store()->getStores(array('777', '666'));
        $productIds = $this->createProductsByNames(
            array(
                'Кит-Кат-343424',
            )
        );

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('...', $output->getDisplay());

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".R.R.R                                               6\nFlushing", $display);

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);
    }

    public function testImportDoubleSalesWithDifferentAmount()
    {
        $storeIds = $this->factory->store()->getStores(array('777', '666'));
        $productIds = $this->createProductsByNames(
            array(
                'Кит-Кат-343424',
            )
        );

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('...', $output->getDisplay());

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);

        static::rebootKernel();

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26-double.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".R.R.R                                               6\nFlushing", $display);

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);
    }

    public function testReturnsImport()
    {
        $storeId = $this->factory->store()->getStoreId('197');

        $skuAmounts = array(
            '1' => -0.57,
            '2' => 0,
            '3' => 20.424,
            '4' => -23,
        );

        $productIds = $this->createProductsByNames(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('purchases-with-returns.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith("........                                             8\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertCount(3, $lines);

        foreach ($skuAmounts as $sku => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $inventory);
        }
    }

    public function testImportSamePurchaseWithDifferentStoreNumber()
    {
        $this->factory->store()->getStores(array('25573', '255731'));
        $this->createProductsByNames(array('10001', '10002'));

        $output = new TestOutput();
        $this->import('SameSame/s25u574-shop1-product2-today-1.xml', $output);
        $display = $output->getDisplay();
        $this->assertStringStartsWith("........................                             24\nFlushing", $display);

        $this->rebootKernel();

        $this->import('SameSame/s25u574-shop2-product2-today-2.xml', $output);
        $display = $output->getDisplay();
        $this->assertStringStartsWith("........................                             24\nFlushing", $display);

        $this->rebootKernel();

        $this->import('SameSame/s25u574-shop2-product1-today-3.xml', $output);
        $display = $output->getDisplay();
        $this->assertStringStartsWith("........................                             24\nFlushing", $display);

    }

    /**
     * @dataProvider importWithDateShiftProvider
     * @param $end
     * @param $start
     * @param string $expectedFirstSaleDate
     * @param string $expectedLastSaleDate
     */
    public function testImportWithDateShift($start, $end, $expectedFirstSaleDate, $expectedLastSaleDate)
    {
        $datePeriod = new DatePeriod($start, $end);
        $skus = array(
            10001 => 4100024386,
            10002 => 4100028615,
            10003 => 4100032110,
            10004 => 4100024121,
            10005 => 4100015156,
            10006 => 4100028598,
            10007 => 4100015128,
            10008 => 4100028614,
            10009 => 4100013729,
            10010 => 4100016759,
            10011 => 4100044260,
            10012 => 4100024805,
            10013 => 4100006687,
            10014 => 4100010948,
            10015 => 4100008977,
            10016 => 4100008384,
            10017 => 4100026668,
            10018 => 4100019469,
            10019 => 4100005287,
            10020 => 4100015218,
            10021 => 4100040147,
            10022 => 4100013059,
            10023 => 4100011403,
            10024 => 4100012829,
            10025 => 4100009714,
            10026 => 4100039535,
            10027 => 4100016284,
            10028 => 4100008269,
            10029 => 4100050468,
            10030 => 4100018314,
            10031 => 4100016575,
            10032 => 4100028790,
            10033 => 4100010310,
            10034 => 4100028661,
            10035 => 4100012731,
            10036 => 4100010299,
            10037 => 4100024800,
            10038 => 4100011238,
            10039 => 4100047658,
            10040 => 4100023591,
            10041 => 4100026511,
            10042 => 4100026501,
            10043 => 4100024318,
            10044 => 4100044867,
            10045 => 4100029907,
            10046 => 4100024195,
            10047 => 4100011231,
            10048 => 4100016706,
            10049 => 4100011239,
            10050 => 4100029437,
            10051 => 4100015044,
            10052 => 4100039604,
            10053 => 4100009828,
            10054 => 4100011225,
            10055 => 4100012831,
            10056 => 4100049821,
            10057 => 4100028803,
            10058 => 4100036816,
            10059 => 4100036815,
            10060 => 4100042168,
            10061 => 4100024670,
            10062 => 4100015252,
            10063 => 4100011425,
            10064 => 4100022002,
            10065 => 4100022000,
            10066 => 4100039819,
            10067 => 4100031693,
            10068 => 4100016381,
            10069 => 4100028588,
            10070 => 4100015167,
            10071 => 4100018939,
            10072 => 4100011435,
            10073 => 4100014242,
            10074 => 4100018941,
            10075 => 4100028804,
            10076 => 4100033415,
            10077 => 4100024453,
            10078 => 4100018176,
            10079 => 4100028585,
            10080 => 4100013062,
            10081 => 4100018083,
            10082 => 4100013072,
            10083 => 4100031675,
            10084 => 4100019565,
            10085 => 4100013097,
        );
        $storeId = $this->factory->store()->getStoreId('701');
        $this->createProductsByNames($skus);

        $importer = $this->import('Kesko/purchases-success-2013.11.04-00.03.09.514.xml', null, null, $datePeriod);

        $this->assertCount(0, $importer->getErrors());

        /* @var ReceiptRepository $receiptRepository */
        $receiptRepository = $this->getContainer()->get('lighthouse.core.document.repository.receipt');

        $utcDateTimeZone = new \DateTimeZone('UTC');

        /* @var Sale $firstSale */
        $firstSale = $receiptRepository->findBy(array('store' => $storeId), array('createdDate' => 1), 1)->getNext();
        $firstSaleCreatedDate = $firstSale->createdDate;
        $firstSaleCreatedDate->setTimezone($utcDateTimeZone);

        /* @var Sale $lastSale */
        $lastSale = $receiptRepository->findBy(array('store' => $storeId), array('createdDate' => -1), 1)->getNext();
        $lastSaleCreatedDate = $lastSale->createdDate;
        $lastSaleCreatedDate->setTimezone($utcDateTimeZone);

        $expectedFirstSaleDate = $this->subDate($expectedFirstSaleDate);
        $expectedLastSaleDate = $this->subDate($expectedLastSaleDate);
        $this->assertStringStartsWith($expectedFirstSaleDate, $firstSaleCreatedDate->format(\DateTime::ISO8601));
        $this->assertStringStartsWith($expectedLastSaleDate, $lastSaleCreatedDate->format(\DateTime::ISO8601));
    }

    /**
     * @param string $date
     * @return string
     */
    protected function subDate($date)
    {
        $matches = null;
        $replace = array();
        if (preg_match_all('/(\\{(.+?)\\|(.+?)\\})/', $date, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $replace[$match[1]] = date(trim($match[3]), strtotime(trim($match[2])));
            }
        }
        return strtr($date, $replace);
    }

    /**
     * @return array
     */
    public function importWithDateShiftProvider()
    {
        return array(
            'no date shift' => array(
                null,
                null,
                '2013-11-03T23:52:27+0000',
                '2013-11-04T00:01:24+0000',
            ),
            '+10 days' => array(
                '+10 days',
                null,
                '2013-11-13T23:52:27+0000',
                '2013-11-14T00:01:24+0000',
            ),
            'today' => array(
                'today',
                '2013-11-04',
                '{yesterday|Y-m-d}T23:52:27+0000',
                '{today|Y-m-d}T00:01:24+0000',
            ),
            'date of first check' => array(
                '2013-11-28 12:00:00',
                '2013-11-03 23:52:27',
                '2013-11-28T12:00:00+0000',
                '2013-11-28T12:08:57+0000',
            ),
            'date of last check' => array(
                '2013-11-28 12:00:00',
                '2013-11-04 00:01:24',
                '2013-11-28T11:51:03+0000',
                '2013-11-28T12:00:00+0000',
            ),
        );
    }

    /**
     * @param string $xmlFile
     * @param OutputInterface $output
     * @param int $batchSize
     * @param DatePeriod $datePeriod
     * @return SalesImporter
     */
    protected function import(
        $xmlFile,
        OutputInterface $output = null,
        $batchSize = null,
        DatePeriod $datePeriod = null
    ) {
        /* @var SalesImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import.sales.importer');
        $xmlFile = $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $xmlFile);
        $parser = new SalesXmlParser($xmlFile);
        $output = ($output) ? : new TestOutput();
        $importer->import($parser, $output, $batchSize, $datePeriod);

        return $importer;
    }

    public function testDuplicateReceipt()
    {
        $storeId = $this->factory->store()->getStoreId('197');

        $nameAmounts = array(
            '10001' => -1,
            '10002' => -1,
            '10003' => 0,
            '10004' => 2,
            '10005' => 1,
            '10006' => -1,
        );

        $productIds = $this->createProductsByNames(array_keys($nameAmounts));

        $output = new TestOutput();
        $this->import('Duplicate/purchase-first.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".....                                                5\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertCount(3, $lines);

        foreach ($nameAmounts as $name => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$name], $inventory);
        }

        $nameAmounts = array(
            '10001' => -2,
            '10002' => 0,
            '10003' => -1,
            '10004' => 1,
            '10005' => -1,
            '10006' => 1,
        );

        $output = new TestOutput();
        $this->import('Duplicate/purchase-with-duplicate.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".R.R.R.R.R                                           10\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertCount(3, $lines);

        foreach ($nameAmounts as $name => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$name], $inventory);
        }
    }
}
