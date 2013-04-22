<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
        $this->client = static::createClient();
    }

    /**
     * @dataProvider productProvider
     */
    public function testPostProductAction(array $postData)
    {
        $crawler = $this->client->request(
            'POST',
            'api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        $this->assertEquals(30.48, $crawler->filter('purchasePrice')->first()->text());
        $this->assertEquals(0, $crawler->filterXPath('//product/lastPurchasePrice')->count());
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostProductInvalidData($expectedCode, array $data, array $assertions = array())
    {
        $postData = $data + $this->getProductData();

        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->runCrawlerAssertions($crawler, $assertions);
    }

    public function testPostProductActionOnlyOneErrorMessageOnNotBlank()
    {
        $invalidData = $this->getProductData();
        $invalidData['purchasePrice'] = '';
        $invalidData['units'] = '';

        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $invalidData)
        );

        Assert::assertResponseCode(400, $this->client);

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
        $this->client->request(
            'POST',
            '/api/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/xml'),
            $xml
        );

        Assert::assertResponseCode(201, $this->client);
    }

    public function testPostProductActionInvalidXmlPost()
    {
        $xml = <<<EOF
not an xml
EOF;
        $this->client->request(
            'POST',
            '/api/1/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/xml'),
            $xml
        );

        Assert::assertResponseCode(400, $this->client);
    }

    public function testPostProductActionEmptyPost()
    {
        $this->client->request(
            'POST',
            '/api/1/products'
        );
        Assert::assertResponseCode(400, $this->client);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPostProductActionUniqueSku(array $postData)
    {
        $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(400, $this->client);

        $this->assertContains(
            'уже есть',
            $crawler->filter('form[name="product"] form[name="sku"] errors entry')->first()->text()
        );
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductAction(array $postData)
    {
        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        $this->assertEquals($postData['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($postData['vat'], $crawler->filter('product vat')->first()->text());

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $putData = $postData;
        $putData['barcode'] = '65346456456';
        $putData['vat'] = 18;

        $this->client->request(
            'PUT',
            '/api/1/products/' . $id,
            array('product' => $putData)
        );

        Assert::assertResponseCode(204, $this->client);

        $crawler = $this->client->request(
            'GET',
            '/api/1/products/' . $id
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertEquals($putData['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($putData['vat'], $crawler->filter('product vat')->first()->text());
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionNotFound(array $putData)
    {
        $id = '1234534312';

        $this->client->request(
            'PUT',
            '/api/1/products/' . $id,
            array('product' => $putData)
        );

        Assert::assertResponseCode(404, $this->client);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionInvalidData(array $postData)
    {
        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        $this->assertEquals($postData['barcode'], $crawler->filter('product barcode')->first()->text());
        $this->assertEquals($postData['vat'], $crawler->filter('product vat')->first()->text());

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $putData = $postData;
        unset($putData['name']);

        $crawler = $this->client->request(
            'PUT',
            '/api/1/products/' . $id,
            array('product' => $putData)
        );

        Assert::assertResponseCode(400, $this->client);

        $this->assertContains(
            'Заполните это поле',
            $crawler->filter('form[name="product"] form[name="name"] errors entry')->first()->text()
        );
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionChangeId(array $postData)
    {
        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);

        $newId = 123;
        $putData = $postData;
        $putData['id'] = $newId;

        $crawler = $this->client->request(
            'PUT',
            '/api/1/products/' . $id,
            array('product' => $putData)
        );

        Assert::assertResponseCode(400, $this->client);
        $this->assertContains(
            'Эта форма не должна содержать дополнительных полей',
            $crawler->filter('form[name="product"] > errors entry')->first()->text()
        );

        $this->client->request(
            'GET',
            '/api/1/products/' . $newId
        );

        Assert::assertResponseCode(404, $this->client);

        $this->client->request(
            'GET',
            '/api/1/products/' . $id
        );

        Assert::assertResponseCode(200, $this->client);
    }

    public function testCorsHeader()
    {
        $postArray = array(
            'name' => 'Кефир',
        );

        $headers = array(
            'HTTP_Origin' => 'www.a.com',
        );

        $this->client->request('POST', '/api/1/product', $postArray, array(), $headers);

        /* @var $response Response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));

        $this->client->request('POST', '/api/1/product', $postArray);
        /* @var $response Response */
        $response = $this->client->getResponse();
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProductsAction(array $postData)
    {
        for ($i = 0; $i < 5; $i++) {
            $postData['name'] = 'Кефир' . $i;
            $postData['sku'] = 'sku' . $i;
            $this->client->request(
                'POST',
                '/api/1/products',
                array('product' => $postData)
            );
            Assert::assertResponseCode(201, $this->client);
        }

        $this->client->request(
            'GET',
            '/api/1/products'
        );

        Assert::assertResponseCode(200, $this->client);
        $this->assertSelectCount('products product', 5, $this->client->getResponse()->getContent());
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProduct(array $postData)
    {
        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $postData)
        );
        $id = $crawler->filter('product id')->first()->text();
        $this->assertNotEmpty($id);
        Assert::assertResponseCode(201, $this->client);

        $this->client->request(
            'GET',
            '/api/1/products/' . $id
        );
        Assert::assertResponseCode(200, $this->client);

        $this->assertEquals('Кефир "Веселый Молочник" 1% 950гр', $crawler->filter('product name')->first()->text());
    }

    public function testGetProductNotFound()
    {
        $this->client->request(
            'GET',
            '/api/1/products/1111'
        );
        Assert::assertResponseCode(404, $this->client);
    }

    /**
     * @dataProvider productProvider
     */
    public function testSearchProductsAction(array $postData)
    {
        for ($i = 0; $i < 5; $i++) {
            $postData['name'] = 'Кефир' . $i;
            $postData['sku'] = 'sku' . $i;
            $this->client->request(
                'POST',
                '/api/1/products',
                array('product' => $postData)
            );
            Assert::assertResponseCode(201, $this->client);
        }

        $crawler = $this->client->request(
            'GET',
            '/api/1/products/name/search',
            array(
                'query' => 'кефир3',
            )
        );

        $this->assertEquals(1, $crawler->filter('product name')->count());
        $this->assertEquals('Кефир3', $crawler->filter('product name')->first()->text());
    }

    public function testSearchProductsActionEmptyRequest()
    {
        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/products/invalid/search.json'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertInternalType('array', $response);
        $this->assertCount(0, $response);
    }

    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'name'
             ***********************************************************************************************/
            'valid name' => array(
                201,
                array('name' => 'test'),
            ),
            'valid name 300 chars' => array(
                201,
                array('name' => str_repeat('z', 300)),
            ),
            'empty name' => array(
                400,
                array('name' => ''),
                array(
                    'form[name="product"] form[name="name"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid name too long' => array(
                400,
                array('name' => str_repeat("z", 305)),
                array(
                    'form[name="product"] form[name="name"] errors entry'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'purchasePrice'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('purchasePrice' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('purchasePrice' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('purchasePrice' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('purchasePrice' => ''),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('purchasePrice' => '10,898'),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('purchasePrice' => '10.898'),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('purchasePrice' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('purchasePrice' => 'not a number'),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('purchasePrice' => 0),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('purchasePrice' => -10),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('purchasePrice' => 2000000001),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('purchasePrice' => '100000000'),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('purchasePrice' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('purchasePrice' => '10000001'),
                array(
                    'form[name="product"] form[name="purchasePrice"] errors entry'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            /***********************************************************************************************
             * 'vat'
             ***********************************************************************************************/
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
                array(
                    'form[name="product"] form[name="vat"] errors entry'
                    =>
                    'Значение должно быть числом.',
                ),
            ),
            'not valid vat negative' => array(
                400,
                array('vat' => -30),
                array(
                    'form[name="product"] form[name="vat"] errors entry'
                    =>
                    'Значение должно быть 0 или больше.',
                ),
            ),
            'not valid vat empty' => array(
                400,
                array('vat' => ''),
                array(
                    'form[name="product"] form[name="vat"] errors entry'
                    =>
                    'Выберите ставку НДС',
                ),
            ),
            /***********************************************************************************************
             * 'barcode'
             ***********************************************************************************************/
            'valid barcode' => array(
                201,
                array('barcode' => 'ijashglkalgh2378rt8237t4rjhdg '),
            ),
            'valid barcode empty' => array(
                201,
                array('barcode' => ''),
            ),
            'valid barcode 200 length' => array(
                201,
                array('barcode' => str_repeat('z', 200)),
            ),
            'not valid barcode too long' => array(
                400,
                array('barcode' => str_repeat("z", 201)),
                array(
                    'form[name="product"] form[name="barcode"] errors entry'
                    =>
                    'Не более 200 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendor'
             ***********************************************************************************************/
            'valid vendor' => array(
                201,
                array('vendor' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendor empty' => array(
                201,
                array('vendor' => ''),
            ),
            'valid vendor 300 length' => array(
                201,
                array('vendor' => str_repeat('z', 300)),
            ),
            'not valid vendor too long' => array(
                400,
                array('vendor' => str_repeat("z", 301)),
                array(
                    'form[name="product"] form[name="vendor"] errors entry'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendorCountry'
             ***********************************************************************************************/
            'valid vendorCountry' => array(
                201,
                array('vendorCountry' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendorCountry empty' => array(
                201,
                array('vendorCountry' => ''),
            ),
            'valid vendorCountry 300 length' => array(
                201,
                array('vendorCountry' => str_repeat('z', 100)),
            ),
            'not valid vendorCountry too long' => array(
                400,
                array('vendorCountry' => str_repeat("z", 101)),
                array(
                    'form[name="product"] form[name="vendorCountry"] errors entry'
                    =>
                    'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'info'
             ***********************************************************************************************/
            'valid info' => array(
                201,
                array('info' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid info empty' => array(
                201,
                array('info' => ''),
            ),
            'valid info 2000 length' => array(
                201,
                array('info' => str_repeat('z', 2000)),
            ),
            'not valid info too long' => array(
                400,
                array('info' => str_repeat("z", 2001)),
                array(
                    'form[name="product"] form[name="info"] errors entry'
                    =>
                    'Не более 2000 символов',
                ),
            ),
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'valid sku' => array(
                201,
                array('sku' => 'qwe223sdw'),
            ),
            'valid sku 100 length' => array(
                201,
                array('sku' => str_repeat('z', 100)),
            ),
            'not valid sku empty' => array(
                400,
                array('sku' => ''),
                array(
                    'form[name="product"] form[name="sku"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 101)),
                array(
                    'form[name="product"] form[name="sku"] errors entry'
                    =>
                    'Не более 100 символов',
                ),
            ),
        );
    }

    /**
     * @dataProvider retailPriceProvider
     */
    public function testPostProductActionSetRetailsPrice(array $postData, array $assertions = array(), array $emptyAssertions = array())
    {
        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/products.json',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $response);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $response);
        }
    }

    /**
     * @dataProvider retailPriceProvider
     */
    public function testPutProductActionSetRetailPrice(array $putData, array $assertions = array(), array $emptyAssertions = array())
    {
        $postData = $this->getProductData();

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/products.json',
            array('product' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        $id = $postResponse['id'];

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/products/' . $id . '.json',
            array('product' => $putData)
        );

        Assert::assertResponseCode(204, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/products/' . $id . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $getResponse);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $getResponse);
        }
    }

    /**
     * @return array
     */
    public function retailPriceProvider()
    {
        $postData = $this->getProductData();

        return array(
            'prefer price, markup invalid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 12,
                    'retailPricePreference' => 'price',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'price',
                )
            ),
            'prefer markup, price invalid' => array(
                array(
                    'retailPrice' => 34.00,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'markup',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'markup',
                )
            ),
            'prefer price, markup valid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'price',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'price',
                )
            ),
            'prefer markup, price valid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'markup',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'markup',
                )
            ),
            'prefer markup, price not entered' => array(
                array(
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'markup',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'markup',
                )
            ),
            'prefer price, markup not entered' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailPricePreference' => 'price',
                ) + $postData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'price',
                )
            ),
            'prefer price, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'price',
                ) + $postData,
                array(
                    'retailPricePreference' => 'price',
                ),
                array(
                    'retailPrice',
                    'retailMarkup',
                )
            ),
            'prefer markup, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'markup',
                ) + $postData,
                array(
                    'retailPricePreference' => 'markup',
                ),
                array(
                    'retailPrice',
                    'retailMarkup',
                )
            ),
        );
    }

    /**
     * @return array
     */
    public function productProvider()
    {
        return array(
            'milkman' => array(
                array(
                    'name' => 'Кефир "Веселый Молочник" 1% 950гр',
                    'units' => 'gr',
                    'barcode' => '4607025392408',
                    'purchasePrice' => 30.48,
                    'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
                    'vat' => 10,
                    'vendor' => 'Вимм-Билль-Данн',
                    'vendorCountry' => 'Россия',
                    'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
                )
            )
        );
    }

    /**
     * @return array
     */
    public function getProductData()
    {
        $productData = $this->productProvider();
        return $productData['milkman'][0];
    }
}
