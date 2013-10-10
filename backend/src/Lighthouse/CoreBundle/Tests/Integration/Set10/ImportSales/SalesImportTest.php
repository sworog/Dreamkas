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
        $this->createStore('197');
        $this->createProductsBySku(
            array(
                1,
                3,
                7,
                8594403916157,
                2873168,
                2809727,
                25525687,
                55557,
                8594403110111,
                4601501082159,
            )
        );

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml', $output);

        $this->assertStringStartsWith('.V............V.....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('products[1].quantity', $lines[2]);
        $this->assertContains('products[1].quantity', $lines[3]);
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
}
