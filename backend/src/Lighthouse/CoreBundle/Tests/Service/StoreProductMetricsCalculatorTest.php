<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Service\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\DateTimestamp;

class StoreProductMetricsCalculatorTest extends WebTestCase
{
    /**
     * @return mixed XML Body
     */
    protected function createXmlBody()
    {
        $templateFile = $this->getFixtureFilePath('Service/StoreProductMetricsCalculator/purchases.xml.tmpl');
        $template = file_get_contents($templateFile);
        $processedTemplate  = preg_replace_callback(
            '/{{\s*(.+?)\s*}}/',
            function (array $matches) {
                $date = new DateTimestamp($matches[1]);
                return $date->format(DateTimestamp::RFC3339_USEC);
            },
            $template
        );
        return $processedTemplate;
    }

    /**
     * @return string xml file path
     */
    protected function createPurchasesXml()
    {
        $xmlBody = $this->createXmlBody();
        $dir = '/tmp/lighthouse_unit' . $this->getPoolPosition() . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $xmlFileName = $dir . uniqid('purchase-', true) . '.xml';
        file_put_contents($xmlFileName, $xmlBody);
        return $xmlFileName;
    }

    protected function importSales()
    {
        $filePath = $this->createPurchasesXml();
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import.sales.importer');
        $parser = new SalesXmlParser($filePath);
        $output = new TestOutput();
        $importer->import($parser, $output);
        $this->assertCount(0, $importer->getErrors());
    }

    /**
     * @param array $products
     * @param string $storeId
     */
    protected function acceptProducts(array $products, $storeId)
    {
        $invoiceData = array(
            'sku' => 'ALL-TOGETHER-234',
            'acceptanceDate' => date('c', strtotime('-32 days')),
        );
        $invoiceId = $this->createInvoice($invoiceData, $storeId);
        foreach ($products as $product) {
            $this->createInvoiceProduct(
                $invoiceId,
                $product['id'],
                $product['accepted'],
                '99.99',
                $storeId
            );
        }
    }

    /**
     * @param array $products
     * @return array
     */
    protected function createProducts(array $products)
    {
        $productIds = $this->createProductsBySku(array_keys($products));
        foreach ($productIds as $sku => $productId) {
            $products[$sku]['id'] = $productId;
            $products[$sku]['sku'] = $sku;
            $products[$sku]['message'] = sprintf("Product '%s' assertions failed", $sku);
        }
        return $products;
    }

    public function testInventoryRatioCalculation()
    {
        $storeId = $this->factory->getStore('197');
        $departmentManager = $this->factory->getDepartmentManager($storeId);
        $products = array(
            '12881231' => array(
                'sold' => 12,
                'accepted' => 30,
                'inventory' => 18,
                'inventoryDays' => 0,
                'averageDailySales' => null,
            ),
            '1' => array(
                'sold' => 112,
                'accepted' => 1000,
                'inventory' => 888,
                'inventoryDays' => 261.17647058824,
                'averageDailySales' => 3.4,
            ),
            '3' => array(
                'sold' => 10,
                'accepted' => 20,
                'inventory' => 10,
                'inventoryDays' => 30,
                'averageDailySales' => 0.33333333333333,
            ),
            '7' => array(
                'sold' => 1,
                'accepted' => 3,
                'inventory' => 2,
                'inventoryDays' => 60,
                'averageDailySales' => 0.033333333333333,
            ),
            '8594403916157' => array(
                'sold' => 2,
                'accepted' => 1,
                'inventory' => -1,
                'inventoryDays' => 0,
                'averageDailySales' => 0.066666666666667,
            ),
            '2873168' => array(
                'sold' => 21,
                'accepted' => 30,
                'inventory' => 9,
                'inventoryDays' => 12.857142857143,
                'averageDailySales' => 0.7,
            ),
            '2809727' => array(
                'sold' => 25,
                'accepted' => 30,
                'inventory' => 5,
                'inventoryDays' => 6,
                'averageDailySales' => 0.83333333333333,
            ),
            '25525687' => array(
                'sold' => 157,
                'accepted' => 180,
                'inventory' => 23,
                'inventoryDays' => 4.3949044585987,
                'averageDailySales' => 5.2333333333333,
            ),
            '55557' => array(
                'sold' => 1,
                'accepted' => 11,
                'inventory' => 10,
                'inventoryDays' => 300,
                'averageDailySales' => 0.033333333333333,
            ),
            '8594403110111' => array(
                'sold' => 1288,
                'accepted' => 1500,
                'inventory' => 212,
                'inventoryDays' => 4.9378881987578,
                'averageDailySales' => 42.933333333333,
            ),
            '4601501082159' => array(
                'sold' => 134,
                'accepted' => 167,
                'inventory' => 33,
                'inventoryDays' => 7.3880597014925,
                'averageDailySales' => 4.4666666666667,
            )
        );

        $products = $this->createProducts($products);
        $this->acceptProducts($products, $storeId, $departmentManager);

        foreach ($products as $product) {
            $assertions = array('inventory' => $product['accepted']);
            $this->assertStoreProduct($storeId, $product['id'], $assertions, $product['message']);
        }

        $this->importSales();

        foreach ($products as $product) {
            $assertions = array('inventory' => $product['inventory']);
            $this->assertStoreProduct($storeId, $product['id'], $assertions, $product['message']);
        }

        /* @var StoreProductMetricsCalculator $metricsCalculator */
        $metricsCalculator = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $metricsCalculator->recalculateDailyAverageSales();

        foreach ($products as $product) {
            $assertions = array(
                'inventory' => $product['inventory'],
                'averageDailySales' => $product['averageDailySales'],
                'inventoryDays' => $product['inventoryDays'],
            );
            $this->assertStoreProduct($storeId, $product['id'], $assertions, $product['message']);
        }
    }
}
