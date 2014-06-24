<?php

namespace Lighthouse\CoreBundle\Tests\Document\Product\Store;

use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

class StoreProductMetricsCalculatorTest extends WebTestCase
{
    /**
     * @return mixed XML Body
     */
    protected function createXmlBody()
    {
        $templateFile = $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases.xml.tmpl');
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
            'acceptanceDate' => date('c', strtotime('-32 days')),
        );
        $builder = $this->factory()->invoice()->createInvoice($invoiceData, $storeId);
        foreach ($products as $product) {
            $builder->createInvoiceProduct($product['id'], $product['accepted'], '99.99');
        }
        $builder->flush();
    }

    /**
     * @param array $products
     * @return array
     */
    protected function createProducts(array $products)
    {
        $productIds = $this->createProductsByNames(array_keys($products));
        foreach ($productIds as $name => $productId) {
            $products[$name]['id'] = $productId;
            $products[$name]['name'] = $name;
            $products[$name]['message'] = sprintf("Product '%s' assertions failed", $name);
        }
        return $products;
    }

    public function testInventoryRatioCalculation()
    {
        $storeId = $this->factory()->store()->getStoreId('197');
        $products = array(
            '10001' => array(
                'sold' => 12,
                'accepted' => 30,
                'inventory' => 18,
                'inventoryDays' => 0,
                'averageDailySales' => 0,
            ),
            '10002' => array(
                'sold' => 312,
                'accepted' => 1000,
                'inventory' => 688,
                'inventoryDays' => '202.4',
                'averageDailySales' => 3.4,
            ),
            '10003' => array(
                'sold' => 10,
                'accepted' => 20,
                'inventory' => 10,
                'inventoryDays' => '30.3',
                'averageDailySales' => '0.33',
            ),
            '10004' => array(
                'sold' => 1,
                'accepted' => 3,
                'inventory' => 2,
                'inventoryDays' => '66.7',
                'averageDailySales' => '0.03',
            ),
            '10005' => array(
                'sold' => 2,
                'accepted' => 1,
                'inventory' => -1,
                'inventoryDays' => '0.0',
                'averageDailySales' => '0.07',
            ),
            '10006' => array(
                'sold' => 21,
                'accepted' => 30,
                'inventory' => 9,
                'inventoryDays' => '12.9',
                'averageDailySales' => 0.7,
            ),
            '10007' => array(
                'sold' => 25,
                'accepted' => 30,
                'inventory' => 5,
                'inventoryDays' => 6,
                'averageDailySales' => '0.83',
            ),
            '10008' => array(
                'sold' => 157,
                'accepted' => 180,
                'inventory' => 23,
                'inventoryDays' => '4.4',
                'averageDailySales' => '5.23',
            ),
            '10009' => array(
                'sold' => 1,
                'accepted' => 11,
                'inventory' => 10,
                'inventoryDays' => '333.3',
                'averageDailySales' => '0.03',
            ),
            '10010' => array(
                'sold' => 1288,
                'accepted' => 1500,
                'inventory' => 212,
                'inventoryDays' => '4.9',
                'averageDailySales' => '42.93',
            ),
            '10011' => array(
                'sold' => 134,
                'accepted' => 167,
                'inventory' => 33,
                'inventoryDays' => '7.4',
                'averageDailySales' => '4.47',
            )
        );

        $products = $this->createProducts($products);
        $this->acceptProducts($products, $storeId);

        foreach ($products as $product) {
            $assertions = array('inventory' => $product['accepted']);
            $this->assertStoreProduct($storeId, $product['id'], $assertions, $product['message']);
        }

        $this->importSales();

        foreach ($products as $product) {
            $assertions = array('inventory' => $product['inventory']);
            $this->assertStoreProduct($storeId, $product['id'], $assertions, $product['message']);
        }

        $this->processJobs();
        /* @var \Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator $metricsCalculator */
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
