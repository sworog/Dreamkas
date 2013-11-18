<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Sales;

use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class SalesImporterTest extends WebTestCase
{
    public function testProductInventoryChangedAfterImport()
    {
        $storeId = $this->factory->getStore('197');

        $skuAmounts = array(
            '1' => '-112',
            '3' => '-10',
            '7' => '-1',
            '8594403916157' => '-1',
            '2873168' => '0',
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
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $inventory);
        }
    }

    public function testImportWithNotFoundProducts()
    {
        $this->factory->getStore('197');
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

        $this->assertStringStartsWith('.E....F......F..E...F..', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(5, $lines);
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Product with sku "2873168" not found', $lines[2]);
        $this->assertContains('Product with sku "2873168" not found', $lines[3]);
    }

    public function testImportWithNotFoundShops()
    {
        $this->factory->getStore('777');
        $this->createProductsBySku(
            array(
                'Кит-Кат-343424',
                'Мит-Мат',
            )
        );

        $output = new TestOutput();
        $this->import('purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('E..', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Store with number "666" not found', $lines[2]);
    }

    public function testImportDoubleSales()
    {
        $storeIds = $this->getStores(array('777', '666'));
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

        $this->assertStringStartsWith('VVV', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[2]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[3]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[4]);

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);
    }

    public function testReturnsImport()
    {
        $storeId = $this->factory->getStore('197');

        $skuAmounts = array(
            '1' => 2.57,
            '2' => 0,
            '3' => 27.58,
            '4' => -23,
        );

        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('purchases-with-returns.xml', $output);

        $this->assertStringStartsWith('....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(1, $lines);

        foreach ($skuAmounts as $sku => $inventory) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $inventory);
        }
    }

    /**
     * @param string $xmlFile
     * @param OutputInterface $output
     * @param int $batchSize
     * @return SalesImporter
     */
    protected function import($xmlFile, OutputInterface $output = null, $batchSize = null)
    {
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import.sales.importer');
        $xmlFile = $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $xmlFile);
        $parser = new SalesXmlParser($xmlFile);
        $output = ($output) ? : new TestOutput();
        $importer->import($parser, $output, $batchSize);

        return $importer;
    }
}
