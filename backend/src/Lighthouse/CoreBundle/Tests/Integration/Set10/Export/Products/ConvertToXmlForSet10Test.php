<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Export\Products;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\ExportProductsWorker;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\Set10Export;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\Set10ProductConverter;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ConvertToXmlForSet10Test extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearJobs();
    }

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
        $storeManager1User = $this->createUser('storeManager1', 'password', 'ROLE_STORE_MANAGER');
        $storeManager1AccessToken = $this->auth($storeManager1User);
        $storeManager2User = $this->createUser('storeManager2', 'password', 'ROLE_STORE_MANAGER');
        $storeManager2AccessToken = $this->auth($storeManager2User);
        $storeManager3User = $this->createUser('storeManager3', 'password', 'ROLE_STORE_MANAGER');

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
                'id' => $this->factory->getStore('1'),
            ),
            2 => array(
                'number' => '2',
                'id' => $this->factory->getStore('2'),
            ),
            3 => array(
                'number' => '3',
                'id' => $this->factory->getStore('3'),
            ),
        );
        $this->factory->linkStoreManagers($storeManager1User->id, $storesData[1]['id']);
        $this->factory->linkStoreManagers($storeManager2User->id, $storesData[2]['id']);
        $this->factory->linkStoreManagers($storeManager3User->id, $storesData[3]['id']);

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
                'purchasePrice' => '',
                'retailPriceMin' => '',
                'retailPriceMax' => '',
                'retailPricePreference' => 'retailPrice',
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
            6 => array(
                'name' => 'Продукт 6',
                'barcode' => '7770000000006',
                'sku' => 'Артикул_продукта_6',
                'vat' => '10',
                'units' => 'liter',
                'vendor' => 'Пончик',
                'vendorCountry' => 'Израиль',
                'purchasePrice' => '88.3',
                'retailMarkupMin' => '0',
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

        $this->clientJsonRequest(
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

        $this->clientJsonRequest(
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
        $productsData = $this->initBase();

        /** @var Set10ProductConverter $converter */
        $converter = $this->getContainer()->get('lighthouse.core.integration.set10.export.products.converter');

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


        $xmlProduct6 = $converter->makeXmlByProduct($productsData[6]['model']);
        $expectedXmlProduct6 = <<<EOF
<good marking-of-the-good="Артикул_продукта_6">
    <shop-indices>1 2 3</shop-indices>
    <name>Продукт 6</name>
    <bar-code code="7770000000006">
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
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct6, $xmlProduct6[0]);

    }

    public function testWriteRemoteFile()
    {
        $this->initBase();

        $xmlFilePath = "/tmp/lighthouse_unit_test";
        if (file_exists($xmlFilePath)) {
            `rm -r $xmlFilePath`;
        }
        mkdir($xmlFilePath . "/source", 0777, true);
        $xmlFileUrl = "file://" . $xmlFilePath;

        $this->createConfig(Set10Export::URL_CONFIG_NAME, $xmlFileUrl);

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
            $this->getFixtureFilePath('Integration/Set10/Export/Products/ExportProducts.xml'),
            array_pop($files)
        );
    }

    public function testExportWorkerGetUrl()
    {
        /** @var \Lighthouse\CoreBundle\Integration\Set10\Export\Products\ExportProductsWorker $worker */
        $worker = $this->getContainer()->get("lighthouse.core.integration.set10.export.products.worker");

        $configUrlId = $this->createConfig(Set10Export::URL_CONFIG_NAME, "smb://test:test@host/centrum/products/");

        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);

        $expectedUrl = "smb://test:test@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://host/centrum/products/");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://host/centrum/products/");
        $configLoginId = $this->createConfig(Set10Export::LOGIN_CONFIG_NAME, "user");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://user@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "user");
        $configPasswordId = $this->createConfig(Set10Export::PASSWORD_CONFIG_NAME, "password");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://user:password@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "");
        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, "password");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "");
        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, "");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://user1:password1@host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "user");
        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, "");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://user@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://user1:password1@host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "user");
        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, "password");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://user:password@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, "smb://user1:password1@host/centrum/products/");
        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, "");
        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, "");
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
        $expectedUrl = "smb://user1:password1@host/centrum/products/";
        $actualUrl = $worker->getUrl();
        $this->assertEquals($expectedUrl, $actualUrl);
    }

    public function testWorkerValidateConfig()
    {
        /** @var ExportProductsWorker $worker */
        $worker = $this->getContainer()->get('lighthouse.core.integration.set10.export.products.worker');

        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $configLoginId = $this->createConfig(Set10Export::LOGIN_CONFIG_NAME, 'user');
        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $configPasswordId = $this->createConfig(Set10Export::PASSWORD_CONFIG_NAME, 'password');
        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, '');
        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, '');
        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $configUrlId = $this->createConfig(Set10Export::URL_CONFIG_NAME, 'smb://test:test@host/centrum/products/');
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, '');
        $validateResult = $worker->validateConfig();
        $this->assertFalse($validateResult);

        $this->updateConfig($configUrlId, Set10Export::URL_CONFIG_NAME, 'file:///tmp/qwe');
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);

        $this->updateConfig($configLoginId, Set10Export::LOGIN_CONFIG_NAME, 'user');
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);

        $this->updateConfig($configPasswordId, Set10Export::PASSWORD_CONFIG_NAME, 'password');
        $validateResult = $worker->validateConfig();
        $this->assertTrue($validateResult);
    }
}
