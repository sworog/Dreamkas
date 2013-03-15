<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    public function testPostProductAction()
    {
        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 30.48,
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

        $content = $client->getResponse()->getContent();
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), $content);
        $this->assertEquals(30.48, $crawler->filter('purchasePrice')->first()->text());
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostProductInvalidData($expectedCode, array $data)
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

        $postArray = array_merge($postArray, $data);

        $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(
            $expectedCode,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    public function testPostProductActionOnlyOneErrorMessageOnNotBlank()
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

        $invalidData = $postArray;
        $invalidData['purchasePrice'] = '';
        $invalidData['units'] = '';

        $crawler = $client->request(
            'POST',
            'api/1/products',
            array('product' => $invalidData)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('form[name="product"] form[name="purchasePrice"] errors entry')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('form[name="product"] form[name="units"] errors entry')->count()
        );
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

    public function testPostProductActionUniqueSku()
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

        $client->restart();

        $crawler = $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        ;
        $this->assertContains(
            'уже есть',
            $crawler->filter('form[name="product"] form[name="sku"] errors entry')->first()->text()
        );
    }

    public function testPutProductAction()
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
            'Заполните это поле',
            $crawler->filter('form[name="product"] form[name="name"] errors entry')->first()->text()
        );
    }

    public function testPutProductActionChangeId()
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

        $crawler = $client->request(
            'POST',
            'api/1/products',
            array('product' => $postArray)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $client->restart();

        $newId = 123;
        $putArray = $postArray;
        $putArray['id'] = $newId;

        $crawler = $client->request(
            'PUT',
            'api/1/products/' . $id,
            array('product' => $putArray)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Эта форма не должна содержать дополнительных полей.',
            $crawler->filter('form[name="product"] > errors entry')->first()->text()
        );

        $client->restart();

        $client->request(
            'GET',
            'api/1/products/' . $newId
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->restart();

        $client->request(
            'GET',
            'api/1/products/' . $id
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
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
            $postArray['sku'] = 'sku' . $i;
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


    public function validateProvider()
    {
        return array(
            'valid name' => array(
                201,
                array('name' => 'test'),
            ),
            'empty name' => array(
                400,
                array('name' => ''),
            ),
            'not valid name too long' => array(
                400,
                array('name' => str_repeat("z", 305)),
            ),
            'valid price dot' => array(
                201,
                array('purchasePrice' => 10.89),
            ),
            'valid price coma' => array(
                201,
                array('purchasePrice' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('purchasePrice' => ''),
            ),
            'not valid price very float' => array(
                400,
                array('purchasePrice' => '10,898'),
                array('form[name="product"] form[name="purchasePrice"] errors entry', '111')
            ),
            'not valid price not a number' => array(
                400,
                array('purchasePrice' => 'not a number'),
            ),
            'not valid price zero' => array(
                400,
                array('purchasePrice' => 0),
            ),
            'not valid price negative' => array(
                400,
                array('purchasePrice' => -10),
            ),
            'not valid price too big' => array(
                400,
                array('purchasePrice' => 2000000001),
            ),
            'valid vat' => array(
                201,
                array('vat' => 18),
            ),
            'valid vat zero' => array(
                201,
                array('vat' => 0),
            ),
            'not valid vat not a number' => array(
                400,
                array('vat' => 'not a number'),
            ),
            'not valid vat negative' => array(
                400,
                array('vat' => -30),
            ),
            'not valid vat empty' => array(
                400,
                array('vat' => ''),
            ),
            'valid barcode' => array(
                201,
                array('barcode' => 'ijashglkalgh2378rt8237t4rjhdg '),
            ),
            'valid barcode empty' => array(
                201,
                array('barcode' => ''),
            ),
            'not valid barcode too long' => array(
                400,
                array('barcode' => str_repeat("z", 201)),
            ),
            'valid vendor' => array(
                201,
                array('vendor' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendor empty' => array(
                201,
                array('vendor' => ''),
            ),
            'not valid vendor too long' => array(
                400,
                array('vendor' => str_repeat("z", 301)),
            ),
            'valid vendorCountry' => array(
                201,
                array('vendorCountry' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendorCountry empty' => array(
                201,
                array('vendorCountry' => ''),
            ),
            'not valid vendorCountry too long' => array(
                400,
                array('vendorCountry' => str_repeat("z", 301)),
            ),
            'valid info' => array(
                201,
                array('info' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid info empty' => array(
                201,
                array('info' => ''),
            ),
            'not valid info too long' => array(
                400,
                array('info' => str_repeat("z", 2001)),
            ),
            'valid sku' => array(
                201,
                array('sku' => 'qwe223sdw'),
            ),
            'not valid sku empty' => array(
                400,
                array('sku' => ''),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 101)),
            ),
        );
    }
}
