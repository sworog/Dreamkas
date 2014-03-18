<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Sales;

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
        $storeId = $this->factory->store()->getStore('197');

        $skuAmounts = array(
            '1' => '-112',
            '3' => '-10',
            '7' => '-1',
            '8594403916157' => '-1',
            '2873168' => '0.008',
            '2809727' => '0',
            '25525687' => '-155',
            '55557' => '-1',
            '8594403110111' => '-1',
            '4601501082159' => '-1',
        );
        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

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
        $this->factory->store()->getStore('197');
        $this->createProductsBySku(
            array(
                '1',
                '3',
                '7',
                '8594403916157',
                '2809727',
                '25525687',
                '55557',
                '8594403110111',
                '4601501082159'
            )
        );

        $output = new TestOutput();
        $this->import('purchases-14-05-2012_9-18-29.xml', $output, 6);

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
        $this->factory->store()->getStore('777');
        $this->createProductsBySku(
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
        $productIds = $this->createProductsBySku(
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
        $productIds = $this->createProductsBySku(
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
        $storeId = $this->factory->store()->getStore('197');

        $skuAmounts = array(
            '1' => -0.57,
            '2' => 0,
            '3' => 20.424,
            '4' => -23,
        );

        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

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
        $this->createProductsBySku(array('25573', '255731'));

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
            4100024386,
            4100028615,
            4100032110,
            4100024121,
            4100015156,
            4100028598,
            4100015128,
            4100028614,
            4100013729,
            4100016759,
            4100044260,
            4100024805,
            4100006687,
            4100010948,
            4100008977,
            4100008384,
            4100026668,
            4100019469,
            4100005287,
            4100015218,
            4100040147,
            4100013059,
            4100011403,
            4100012829,
            4100009714,
            4100039535,
            4100016284,
            4100008269,
            4100050468,
            4100018314,
            4100016575,
            4100028790,
            4100010310,
            4100028661,
            4100012731,
            4100010299,
            4100024800,
            4100011238,
            4100047658,
            4100023591,
            4100026511,
            4100026501,
            4100024318,
            4100044867,
            4100029907,
            4100024195,
            4100011231,
            4100016706,
            4100011239,
            4100029437,
            4100015044,
            4100039604,
            4100009828,
            4100011225,
            4100012831,
            4100049821,
            4100028803,
            4100036816,
            4100036815,
            4100042168,
            4100024670,
            4100015252,
            4100011425,
            4100022002,
            4100022000,
            4100039819,
            4100031693,
            4100016381,
            4100028588,
            4100015167,
            4100018939,
            4100011435,
            4100014242,
            4100018941,
            4100028804,
            4100033415,
            4100024453,
            4100018176,
            4100028585,
            4100013062,
            4100018083,
            4100013072,
            4100031675,
            4100019565,
            4100013097,
        );
        $storeId = $this->factory->store()->getStore('701');
        $this->createProductsBySku($skus);

        $importer = $this->import('Kesko/purchases-success-2013.11.04-00.03.09.514.xml', null, null, $datePeriod);

        $this->assertCount(0, $importer->getErrors());

        $salesRepository = $this->getContainer()->get('lighthouse.core.document.repository.receipt');

        $utcDateTimeZone = new \DateTimeZone('UTC');

        /* @var Sale $firstSale */
        $firstSale = $salesRepository->findBy(array('store' => $storeId), array('createdDate' => 1), 1)->getNext();
        $firstSaleCreatedDate = $firstSale->createdDate;
        $firstSaleCreatedDate->setTimezone($utcDateTimeZone);

        /* @var Sale $lastSale */
        $lastSale = $salesRepository->findBy(array('store' => $storeId), array('createdDate' => -1), 1)->getNext();
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
        $storeId = $this->factory->store()->getStore('197');

        $skuAmounts = array(
            '1' => -1,
            '2' => -1,
            '3' => 0,
            '4' => 2,
            '5' => 1,
            '6' => -1,
        );

        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('Duplicate/purchase-first.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".....                                                5\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertCount(3, $lines);

        foreach ($skuAmounts as $sku => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $inventory);
        }

        $skuAmounts = array(
            '1' => -2,
            '2' => 0,
            '3' => -1,
            '4' => 1,
            '5' => -1,
            '6' => 1,
        );

        $output = new TestOutput();
        $this->import('Duplicate/purchase-with-duplicate.xml', $output);

        $display = $output->getDisplay();
        $this->assertStringStartsWith(".R.R.R.R.R                                           10\nFlushing", $display);
        $lines = $output->getLines();
        $this->assertCount(3, $lines);

        foreach ($skuAmounts as $sku => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $inventory);
        }
    }
}
