<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function testPostProductAction()
    {
        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testPostProductActionXmlPost()
    {
        $client = static::createClient();

        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<product>
    <name>Кефир "Веселый Молочник" 1% 950гр</name>
    <units>gr</units>
    <barcode>4607025392408</barcode>
    <purchasePrice>3048</purchasePrice>
    <sku>КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР</sku>
    <vat>10</vat>
    <vendor>Вимм-Билль-Данн</vendor>
    <vendorCountry>Россия</vendorCountry>
    <info>Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса</info>
</product>
EOF;
        $client->request(
            'POST',
            'api/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/xml'),
            $xml
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testPostProductActionInvalidXmlPost()
    {
        $client = static::createClient();

        $xml = <<<EOF
not an xml
EOF;
        $client->request(
            'POST',
            'api/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/xml'),
            $xml
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPostProductActionEmptyPost()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/1/products'
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPutProductAction()
    {
        $this->clearMongoDb();

        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $crawler = $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $this->assertEquals($postArray['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($postArray['vat'], $crawler->filter('product vat')->first()->text());

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $client->restart();

        $putArray = $postArray;
        $putArray['barcode'] = '65346456456';
        $putArray['vat'] = 18;

        $client->request(
            'PUT',
            'api/1/products/' . $id,
            array('product' => $putArray)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->restart();

        $crawler = $client->request(
            'GET',
            'api/1/products/' . $id
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals($putArray['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($putArray['vat'], $crawler->filter('product vat')->first()->text());
    }

    public function testPutProductActionNotFound()
    {
        $this->clearMongoDb();

        $client = static::createClient();

        $id = '1234534312';

        $putArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $client->request(
            'PUT',
            'api/1/products/' . $id,
            array('product' => $putArray)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPutProductActionInvalidData()
    {
        $this->clearMongoDb();

        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $crawler = $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $this->assertEquals($postArray['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($postArray['vat'], $crawler->filter('product vat')->first()->text());

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $client->restart();

        $putArray = $postArray;
        unset($putArray['name']);

        $crawler = $client->request(
            'PUT',
            'api/1/products/' . $id,
            array('product' => $putArray)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $this->assertContains(
            'should not be blank',
            $crawler->filter('form[name="product"] form[name="name"] errors entry')->first()->text()
        );
    }

    public function testCorsHeader()
    {
        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир',
        );

        $headers = array(
            'HTTP_Origin' => 'www.a.com',
        );

        $client->request('POST', 'api/1/product', $postArray, array(), $headers);

        /* @var $response Response */
        $response = $client->getResponse();
        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));

        $client->request('POST', 'api/1/product', $postArray);
        /* @var $response Response */
        $response = $client->getResponse();
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }

    public function testGetProductsAction()
    {
        $this->clearMongoDb();

        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );
        for ($i = 0; $i < 5; $i++) {
            $postArray['name'] = 'Кефир' . $i;
            $client->request(
                'POST',
                '/api/1/products',
                array('product' => $postArray)
            );
            $this->assertEquals(201, $client->getResponse()->getStatusCode());
            $client->restart();
        }

        $client->request(
            'GET',
            'api/1/products'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectCount('products product', 5, $client->getResponse()->getContent());
    }

    public function testGetProduct()
    {
        $this->clearMongoDb();

        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $crawler = $client->request(
            'POST',
            '/api/1/products',
            array('product' => $postArray)
        );
        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $client->restart();

        $client->request(
            'GET',
            'api/1/products/' . $id
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals('Кефир "Веселый Молочник" 1% 950гр', $crawler->filter('product name')->first()->text());
    }

    public function testGetProductNotFound()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            'api/1/products/1111'
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
