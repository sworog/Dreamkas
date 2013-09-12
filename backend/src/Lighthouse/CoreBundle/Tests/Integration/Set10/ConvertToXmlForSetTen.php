<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Integration\Set10\Set10;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ConvertToXmlForSetTen extends WebTestCase
{
    /**
     * @return StoreProductRepository
     */
    public function getStoreProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.store_product');
    }

    /**
     * @return ProductRepository
     */
    public function getProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.product');
    }

    /**
     * @return array
     */
    public function initBase()
    {
        $administratorAccessToken = $this->authAsRole('ROLE_ADMINISTRATOR');
        $storeManager1User = $this->createUser('storeManager1', 'password', 'ROLE_STORE_MANAGER');
        $storeManager1AccessToken = $this->auth($storeManager1User);
        $storeManager2User = $this->createUser('storeManager2', 'password', 'ROLE_STORE_MANAGER');
        $storeManager2AccessToken = $this->auth($storeManager2User);
        $storeManager3User = $this->createUser('storeManager3', 'password', 'ROLE_STORE_MANAGER');
        $storeManager3AccessToken = $this->auth($storeManager3User);

        $groupData = array(
            'name' => 'Группа',
            'id' => $this->createGroup('Группа'),
        );

        $categoryData = array(
            'name' => 'Категория',
            'id' => $this->createCategory($groupData['id'], 'Категория'),
        );

        $subCategoryData = array(
            'name' => 'Подкатегория',
            'id' => $this->createSubCategory($categoryData['id'], 'Подкатегория'),
        );

        $storesData = array(
            1 => array(
                'number' => '1',
                'id' => $this->createStore('1'),
            ),
            2 => array(
                'number' => '2',
                'id' => $this->createStore('2'),
            ),
            3 => array(
                'number' => '3',
                'id' => $this->createStore('3'),
            ),
        );
        $this->linkStoreManagers($storesData[1]['id'], $storeManager1User->id);
        $this->linkStoreManagers($storesData[2]['id'], $storeManager2User->id);
        $this->linkStoreManagers($storesData[3]['id'], $storeManager3User->id);

        $productsData = array(
            1 => array(
                'name' => 'Продукт 1',
                'barcode' => '7770000000001',
                'sku' => 'Артикул_продукта_1',
                'vat' => '10',
                'units' => 'kg',
                'vendor' => 'Вимм-Билль-Данн',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '44.11',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],

            ),
            2 => array(
                'name' => 'Продукт 2 без диапозонов',
                'barcode' => '7770000000002',
                'sku' => 'Артикул_продукта_2',
                'vat' => '10',
                'units' => 'liter',
                'vendor' => 'Петмол',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '55',
                'subCategory' => $subCategoryData['id'],
            ),
            3 => array(
                'name' => 'Продукт 3',
                'barcode' => '7770000000003',
                'sku' => 'Артикул_продукта_3',
                'vat' => '10',
                'units' => 'unit',
                'vendor' => 'Куромать',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '66.23',
                'retailPriceMin' => '67.33',
                'retailPriceMax' => '117.54',
                'retailPricePreference' => 'retailPrice',
                'subCategory' => $subCategoryData['id'],
            ),
            4 => array(
                'name' => 'Продукт 4 без цены',
                'barcode' => '7770000000004',
                'sku' => 'Артикул_продукта_4',
                'vat' => '10',
                'units' => 'kg',
                'vendor' => 'Гадило',
                'vendorCountry' => 'Россия',
                'subCategory' => $subCategoryData['id'],
            ),
            5 => array(
                'name' => 'Продукт 5',
                'barcode' => '7770000000005',
                'sku' => 'Артикул_продукта_5',
                'vat' => '10',
                'units' => 'liter',
                'vendor' => 'Пончик',
                'vendorCountry' => 'Израиль',
                'purchasePrice' => '88.3',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],
            ),
        );

        $productRepository = $this->getProductRepository();
        for ($key = 1; $key < count($productsData) + 1; $key++) {
            $productsData[$key]['id'] = $this->createProduct($productsData[$key], $productsData[$key]['subCategory']);
            $productsData[$key]['model'] = $productRepository->find($productsData[$key]['id']);
        }

        $store1Product1Data = array(
            'retailMarkup' => '45',
            'retailPricePreference' => 'retailMarkup',
        );

        $response = $this->clientJsonRequest(
            $storeManager1AccessToken,
            'PUT',
            '/api/1/stores/' . $storesData[1]['id'] . '/products/' . $productsData[1]['id'],
            $store1Product1Data
        );

        $this->assertResponseCode(200);

        $store2Product3Data = array(
            'retailPrice' => '76.93',
            'retailPricePreference' => 'retailPrice',
        );

        $response = $this->clientJsonRequest(
            $storeManager2AccessToken,
            'PUT',
            '/api/1/stores/' . $storesData[2]['id'] . '/products/' . $productsData[3]['id'],
            $store2Product3Data
        );

        $this->assertResponseCode(200);

        return $productsData;
    }

    public function testConvertProductsToXml()
    {
        $this->clearMongoDb();

        $productsData = $this->initBase();

        $converter = $this->getContainer()->get('lighthouse.core.service.convert.set10.product');

        $xmlProduct1 = $converter->makeXmlByProduct($productsData[1]['model']);
        $expectedXmlProduct11 = <<<EOF
<good marking-of-the-good="Артикул_продукта_1">
    <shop-indices>1</shop-indices>
    <name>Продукт 1</name>
    <bar-code code="7770000000001">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <product-type>ProductPieceEntity</product-type>
    <price-entry price="63.96">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Подкатегория">
        <name>Подкатегория</name>
        <parent-group id="Категория">
            <name>Категория</name>
            <parent-group id="Группа">
                <name>Группа</name>
            </parent-group>
        </parent-group>
    </group>
    <measure-type id="kg">
        <name>кг</name>
    </measure-type>
    <plugin-property key="precision" value="1"/>
</good>
EOF;
        $expectedXmlProduct12 = <<<EOF
<good marking-of-the-good="Артикул_продукта_1">
    <shop-indices>2 3</shop-indices>
    <name>Продукт 1</name>
    <bar-code code="7770000000001">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <product-type>ProductPieceEntity</product-type>
    <price-entry price="70.58">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Подкатегория">
        <name>Подкатегория</name>
        <parent-group id="Категория">
            <name>Категория</name>
            <parent-group id="Группа">
                <name>Группа</name>
            </parent-group>
        </parent-group>
    </group>
    <measure-type id="kg">
        <name>кг</name>
    </measure-type>
    <plugin-property key="precision" value="1"/>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct11, $xmlProduct1[0]);
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct12, $xmlProduct1[1]);


        $xmlProduct2 = $converter->makeXmlByProduct($productsData[2]['model']);
        $this->assertEmpty($xmlProduct2);


        $xmlProduct3 = $converter->makeXmlByProduct($productsData[3]['model']);
        $expectedXmlProduct31 = <<<EOF
<good marking-of-the-good="Артикул_продукта_3">
    <shop-indices>2</shop-indices>
    <name>Продукт 3</name>
    <bar-code code="7770000000003">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <product-type>ProductPieceEntity</product-type>
    <price-entry price="76.93">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Подкатегория">
        <name>Подкатегория</name>
        <parent-group id="Категория">
            <name>Категория</name>
            <parent-group id="Группа">
                <name>Группа</name>
            </parent-group>
        </parent-group>
    </group>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
    <plugin-property key="precision" value="1"/>
</good>
EOF;
        $expectedXmlProduct32 = <<<EOF
<good marking-of-the-good="Артикул_продукта_3">
    <shop-indices>1 3</shop-indices>
    <name>Продукт 3</name>
    <bar-code code="7770000000003">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <product-type>ProductPieceEntity</product-type>
    <price-entry price="117.54">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Подкатегория">
        <name>Подкатегория</name>
        <parent-group id="Категория">
            <name>Категория</name>
            <parent-group id="Группа">
                <name>Группа</name>
            </parent-group>
        </parent-group>
    </group>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
    <plugin-property key="precision" value="1"/>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct31, $xmlProduct3[0]);
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct32, $xmlProduct3[1]);


        $xmlProduct4 = $converter->makeXmlByProduct($productsData[4]['model']);
        $this->assertEmpty($xmlProduct4);


        $xmlProduct5 = $converter->makeXmlByProduct($productsData[5]['model']);
        $expectedXmlProduct5 = <<<EOF
<good marking-of-the-good="Артикул_продукта_5">
    <shop-indices>1 2 3</shop-indices>
    <name>Продукт 5</name>
    <bar-code code="7770000000005">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <product-type>ProductPieceEntity</product-type>
    <price-entry price="141.28">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Подкатегория">
        <name>Подкатегория</name>
        <parent-group id="Категория">
            <name>Категория</name>
            <parent-group id="Группа">
                <name>Группа</name>
            </parent-group>
        </parent-group>
    </group>
    <measure-type id="liter">
        <name>л</name>
    </measure-type>
    <plugin-property key="precision" value="1"/>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct5, $xmlProduct5[0]);
    }

    public function testWriteRemoteFile()
    {
        $this->clearMongoDb();

        $productData = $this->initBase();

        $xmlFilePath = "/tmp/lighthouse_unit_test";
        if (file_exists($xmlFilePath)) {
            `rm -r $xmlFilePath`;
        }
        mkdir($xmlFilePath . "/source", 0777, true);
        $xmlFileUrl = "file://" . $xmlFilePath;

        $this->createConfig(Set10::URL_CONFIG_NAME, $xmlFileUrl);

        $commercialAccessToken = $this->authAsRole("ROLE_COMMERCIAL_MANAGER");
        $this->clientJsonRequest(
            $commercialAccessToken,
            "GET",
            "/api/1/integration/export/products"
        );

        $this->assertResponseCode(200);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('set10_export_products', '*.type', $getResponse);
        Assert::assertJsonPathEquals('pending', '*.status', $getResponse);

        /* @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $dm->clear();

        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');

        $jobManager->startWatchingTubes();
        $job = $jobManager->reserveJob(0);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('set10_export_products', '*.type', $getResponse);
        Assert::assertJsonPathEquals('processing', '*.status', $getResponse);

        $jobManager->startWatchingTubes();
        $jobManager->processJob($job);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('set10_export_products', '*.type', $getResponse);
        Assert::assertJsonPathEquals('success', '*.status', $getResponse);

        $files = glob($xmlFilePath . "/source/*");
        $this->assertXmlFileEqualsXmlFile(
            __DIR__ . "/../../Fixtures/Integration/Set10/ExportProducts.xml",
            array_pop($files)
        );
    }
}
