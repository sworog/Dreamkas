<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\ImportSales;

use Lighthouse\CoreBundle\Integration\Set10\ImportSales\ImportSalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\SalesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class SalesImportTest extends WebTestCase
{
    /**
     * @param string $xmlFile
     * @param OutputInterface $output
     * @param int $batchSize
     * @return SalesImporter
     */
    protected function import($xmlFile, OutputInterface $output = null, $batchSize = null)
    {
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import_sales.importer');
        $xmlFile = $this->getFixtureFilePath($xmlFile);
        $parser = new ImportSalesXmlParser($xmlFile);
        $output = ($output) ?: new TestOutput();
        $importer->import($parser, $output, $batchSize);
        return $importer;
    }

    public function testImportWithSeveralInvalidCounts()
    {
        $storeId = $this->createStore('197');

        $skuAmounts = array(
            '1' => -112,
            '3' => -10,
            '7' => -1,
            '8594403916157' => -1,
            '2873168' => 0,
            '2809727' => 0,
            '25525687' => -159,
            '55557' => -1,
            '8594403110111' => -1,
            '4601501082159' => -1,
        );
        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml', $output);

        $this->assertStringStartsWith('.V............V.....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('products[1].quantity', $lines[2]);
        $this->assertContains('products[1].quantity', $lines[3]);

        foreach ($skuAmounts as $sku => $amount) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $amount);
        }
    }

    public function testImportWithNotFoundProducts()
    {
        $this->createStore('197');
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
        $this->import('Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml', $output, 6);

        $this->assertStringStartsWith('.E....F......F..E...F..', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Product with sku "2873168" not found', $lines[2]);
        $this->assertContains('Product with sku "2873168" not found', $lines[3]);
    }

    public function testImportWithNotFoundShops()
    {
        $this->createStore('777');
        $this->createProductsBySku(
            array(
                'Кит-Кат-343424',
                'Мит-Мат',
            )
        );

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportSales/purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('E..', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Store with number "666" not found', $lines[2]);
    }

    public function testImportDoubleSales()
    {
        $storeIds = $this->createStores(array('777', '666'));
        $productIds = $this->createProductsBySku(
            array(
                'Кит-Кат-343424',
            )
        );

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportSales/purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('...', $output->getDisplay());

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportSales/purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('VVV', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[2]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[3]);
        $this->assertContains('Такая продажа уже зарегистрированна в системе', $lines[4]);

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);
    }
}
