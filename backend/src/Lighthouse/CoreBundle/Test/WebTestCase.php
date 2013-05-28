<?php

namespace Lighthouse\CoreBundle\Test;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use AppKernel;

/**
 * @codeCoverageIgnore
 */
class WebTestCase extends BaseTestCase
{
    /**
     * Init app with debug
     * @var bool
     */
    static protected $appDebug = true;

    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @return AppKernel
     */
    protected static function initKernel()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        return static::$kernel;
    }

    /**
     * @param array $options
     * @return AppKernel
     */
    protected static function createKernel(array $options = array())
    {
        $options['debug'] = isset($options['debug']) ? $options['debug'] : static::$appDebug;
        return parent::createKernel($options);
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return static::initKernel()->getContainer();
    }

    protected function clearMongoDb()
    {
        /* @var DocumentManager $mongoDb */
        $mongoDb = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $mongoDb->getSchemaManager()->dropCollections();
        $mongoDb->getSchemaManager()->createCollections();
        $mongoDb->getSchemaManager()->ensureIndexes();
    }

    /**
     * @param Crawler $crawler
     * @param array $assertions
     * @param bool $xpath
     */
    protected function runCrawlerAssertions(Crawler $crawler, array $assertions, $xpath = false)
    {
        foreach ($assertions as $selector => $expected) {
            $filtered = ($xpath) ? $crawler->filterXPath($selector) : $crawler->filter($selector);
            $this->assertContains($expected, $filtered->first()->text());
        }
    }

    /**
     * @param Client $client
     * @param string $method
     * @param string $uri
     * @param mixed  $data
     * @param array $parameters
     * @param array $server
     * @param bool $changeHistory
     * @return mixed
     * @throws \UnexpectedValueException
     */
    protected function clientJsonRequest(
        Client $client,
        $method,
        $uri,
        $data = null,
        array $parameters = array(),
        array $server = array(),
        $changeHistory = true
    ) {
        if (null !== $data) {
            $json = json_encode($data);
        } else {
            $json = null;
        }

        if (!isset($server['CONTENT_TYPE'])) {
            $server['CONTENT_TYPE'] = 'application/json';
        }
        if (!isset($server['HTTP_ACCEPT'])) {
            $server['HTTP_ACCEPT'] = 'application/json, */*; q=0.01';
        }

        $client->request(
            $method,
            $uri,
            $parameters,
            array(),
            $server,
            $json,
            $changeHistory
        );

        $content = $client->getResponse()->getContent();
        $json = json_decode($content, true);

        if (0 != json_last_error()) {
            throw new \UnexpectedValueException('Failed to parse json: ' . $content);
        }

        return $json;
    }

    /**
     * @param array $modifiedData
     * @return string
     */
    protected function createInvoice(array $modifiedData = array())
    {
        $invoiceData = $modifiedData + array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices.json',
            array('invoice' => $invoiceData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $invoiceId
     * @param stirng $productId
     * @param int $quantity
     * @param float $price
     * @return string
     */
    public function createInvoiceProduct($invoiceId, $productId, $quantity, $price)
    {
        $invoiceProductData = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        Assert::assertResponseCode(201, $this->client);

        return $postResponse['id'];
    }

    public function createPurchaseWithProduct($productId, $sellingPrice, $quantity, $date = 'now')
    {
        $purchaseProductData = array(
            'product' => $productId,
            'sellingPrice' => $sellingPrice,
            'quantity' => $quantity,
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/purchases.json',
            array('purchase' => array(
                'createdDate' => date('c', strtotime($date)),
                'products' => array($purchaseProductData),
            ))
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $extra
     * @return string
     */
    protected function createProduct($extra = '')
    {
        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр' . $extra,
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР' . $extra,
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/products.json',
            array('product' => $productData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param $productId
     * @param $invoiceId
     * @return array
     */
    protected function createProducts($productId, $invoiceId)
    {
        $productsData = array(
            array(
                'product' => $productId,
                'quantity' => 10,
                'price' => 11.12,
                'productAmount' => 10,
            ),
            array(
                'product' => $productId,
                'quantity' => 5,
                'price' => 12.76,
                'productAmount' => 15,
            ),
            array(
                'product' => $productId,
                'quantity' => 1,
                'price' => 5.99,
                'productAmount' => 16,
            ),
        );

        foreach ($productsData as $i => $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'product' => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $this->client,
                'POST',
                '/api/1/invoices/' . $invoiceId . '/products.json',
                array('invoiceProduct' => $invoiceProductData)
            );

            Assert::assertResponseCode(201, $this->client);
            $productsData[$i]['id'] = $response['id'];
        }

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(3, "*.id", $getResponse);

        foreach ($productsData as $productData) {
            Assert::assertJsonPathEquals($productData['id'], '*.id', $getResponse);
        }

        return $productsData;
    }

    /**
     * @param string $number
     * @param int $date
     * @param string $productId
     * @param float $price
     * @param int $quantity
     * @param string $cause
     */
    protected function createWriteOff($number = '431-6782', $date = null)
    {
        $date = $date ? : date('c', strtotime('-1 day'));

        $postData = array(
            'number' => $number,
            'date' => $date,
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $writeOffId
     * @param string $productId
     * @param float $price
     * @param int $quantity
     * @param string $cause
     * @return string
     */
    protected function createWriteOffProduct($writeOffId, $productId, $price = 5.99, $quantity = 10, $cause = 'Порча')
    {
        $postData = array(
            'product' => $productId,
            'price' => $price,
            'quantity' => $quantity,
            'cause' => $cause,
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/' . $writeOffId . '/products.json',
            array('writeOffProduct' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $name
     * @return string
     */
    protected function createKlass($name = 'Продовольственные товары')
    {
        $postData = array(
            'name' => $name,
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses.json',
            array('klass' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param mixed $json
     * @param array $assertions
     * @param bool  $contains
     */
    protected function performJsonAssertions($json, array $assertions, $contains = false)
    {
        foreach ($assertions as $path => $expected) {
            if (null === $expected) {
                Assert::assertNotJsonHasPath($path, $json);
            } elseif ($contains) {
                Assert::assertJsonPathContains($expected, $path, $json);
            } else {
                Assert::assertJsonPathEquals($expected, $path, $json);
            }
        }
    }

    /**
     * @param string $productId
     * @param array $assertions
     */
    protected function assertProduct($productId, array $assertions)
    {
        $productJson = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/products/' . $productId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->performJsonAssertions($productJson, $assertions);
    }

    /**
     * @param string $klassId
     * @param string $name
     * @return string
     */
    protected function createGroup($klassId, $name = 'Винно-водочные изделия')
    {
        $groupData = array(
            'name' => $name
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/' . $klassId . '/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }
}
