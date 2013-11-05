<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\ImportCheques;

use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ImportChequesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ChequesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class ChequesImportTest extends IntegrationTestCase
{
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
            '25525687' => -155,
            '55557' => -1,
            '8594403110111' => -1,
            '4601501082159' => -1,
        );
        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportCheques/purchases-14-05-2012_9-18-29.xml', $output);

        $this->assertStringStartsWith('.V............V.....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(5, $lines);
        $this->assertContains('Errors', $lines[1]);
        $this->assertContains('products[1].quantity', $lines[2]);

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
        $this->import('Integration/Set10/ImportCheques/purchases-14-05-2012_9-18-29.xml', $output, 6);

        $this->assertStringStartsWith('.E....F......F..E...F..', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(5, $lines);
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
        $this->import('Integration/Set10/ImportCheques/purchases-13-09-2013_15-09-26.xml', $output);

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
        $this->import('Integration/Set10/ImportCheques/purchases-13-09-2013_15-09-26.xml', $output);

        $this->assertStringStartsWith('...', $output->getDisplay());

        $this->assertStoreProductTotals($storeIds['666'], $productIds['Кит-Кат-343424'], -1);
        $this->assertStoreProductTotals($storeIds['777'], $productIds['Кит-Кат-343424'], -2);

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportCheques/purchases-13-09-2013_15-09-26.xml', $output);

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
        $storeId = $this->createStore('197');

        $skuAmounts = array(
            '1' => 1,
            '2' => 0,
            '3' => 24,
            '4' => -23,
        );

        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('Integration/Set10/ImportCheques/purchases-with-restitution.xml', $output);

        $this->assertStringStartsWith('....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(1, $lines);

        foreach ($skuAmounts as $sku => $amount) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $amount);
        }
    }
}
