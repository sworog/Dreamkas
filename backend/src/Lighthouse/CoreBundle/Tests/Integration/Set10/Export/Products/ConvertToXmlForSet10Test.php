<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Export\Products;

use Lighthouse\CoreBundle\Document\Job\Integration\Set10\ExportProductsJob;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Type\AlcoholType;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\ExportProductsWorker;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\Set10Export;
use Lighthouse\CoreBundle\Integration\Set10\Export\Products\Set10ProductConverter;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

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
     * @return Set10ProductConverter
     */
    public function getConverter()
    {
        return $this->getContainer()->get('lighthouse.core.integration.set10.export.products.converter');
    }

    /**
     * @return array
     */
    public function initBase()
    {
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
                'id' => $this->factory()->store()->getStoreId('1'),
            ),
            2 => array(
                'number' => '2',
                'id' => $this->factory()->store()->getStoreId('2'),
            ),
            3 => array(
                'number' => '3',
                'id' => $this->factory()->store()->getStoreId('3'),
            ),
        );

        $storeManager1AccessToken = $this->factory()->oauth()->authAsStoreManager($storesData[1]['id']);
        $storeManager2AccessToken = $this->factory()->oauth()->authAsStoreManager($storesData[2]['id']);
        $this->factory()->oauth()->authAsStoreManager($storesData[3]['id']);

        $productsData = array(
            1 => array(
                'name' => 'Продукт 1',
                'barcode' => '7770000000001',
                'vat' => '10',
                'type' => WeightType::TYPE,
                'vendor' => 'Вимм-Билль-Данн',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '44.11',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],
                'typeProperties' => array(
                    'nameOnScales' => 'Название на весах',
                    'descriptionOnScales' => "Описание\nна весах",
                    'ingredients' => '<масло,соль,перец>',
                    'shelfLife' => '23',
                    'nutritionFacts' => '"Углеводы - 12гр"'
                )
            ),
            2 => array(
                'name' => 'Продукт 2 без диапозонов',
                'barcode' => '7770000000002',
                'vat' => '10',
                'type' => UnitType::TYPE,
                'vendor' => 'Петмол',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '55',
                'subCategory' => $subCategoryData['id'],
            ),
            3 => array(
                'name' => 'Продукт 3',
                'barcode' => '7770000000003',
                'vat' => '10',
                'type' => UnitType::TYPE,
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
                'vat' => '10',
                'type' => WeightType::TYPE,
                'vendor' => 'Гадило',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '',
                'retailPriceMin' => '',
                'retailPriceMax' => '',
                'retailPricePreference' => 'retailPrice',
                'subCategory' => $subCategoryData['id'],
            ),
            5 => array(
                'name' => 'Виски 365 Дней',
                'barcode' => '7770000000005',
                'vat' => '10',
                'type' => AlcoholType::TYPE,
                'vendor' => 'Пончик',
                'vendorCountry' => 'Израиль',
                'purchasePrice' => '88.3',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],
                'typeProperties' => array(
                    'alcoholByVolume' => '38,5',
                    'volume' => '0,375'
                )
            ),
            6 => array(
                'name' => 'Продукт 6',
                'barcode' => '7770000000006',
                'vat' => '10',
                'type' => UnitType::TYPE,
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

        $converter = $this->getConverter();

        $xmlProduct1 = $converter->makeXmlByProduct($productsData[1]['model'], false);
        $expectedXmlProduct11 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10001">
    <shop-indices>1</shop-indices>
    <name>Продукт 1</name>
    <bar-code code="7770000000001">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductWeightEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <plugin-property key="name-on-scale-screen" value="Название на весах"/>
    <plugin-property key="description-on-scale-screen" value="Описание&#10;на весах"/>
    <plugin-property key="composition" value="&lt;масло,соль,перец&gt;"/>
    <plugin-property key="food-value" value="&quot;Углеводы - 12гр&quot;"/>
    <plugin-property key="good-for-hours" value="23"/>
    <measure-type id="kg">
        <name>кг</name>
    </measure-type>
</good>
EOF;
        $expectedXmlProduct12 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10001">
    <shop-indices>2 3</shop-indices>
    <name>Продукт 1</name>
    <bar-code code="7770000000001">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductWeightEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <plugin-property key="name-on-scale-screen" value="Название на весах"/>
    <plugin-property key="description-on-scale-screen" value="Описание&#10;на весах"/>
    <plugin-property key="composition" value="&lt;масло,соль,перец&gt;"/>
    <plugin-property key="food-value" value="&quot;Углеводы - 12гр&quot;"/>
    <plugin-property key="good-for-hours" value="23"/>
    <measure-type id="kg">
        <name>кг</name>
    </measure-type>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct11, $xmlProduct1[0]);
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct12, $xmlProduct1[1]);


        $xmlProduct2 = $converter->makeXmlByProduct($productsData[2]['model'], false);
        $this->assertEmpty($xmlProduct2);


        $xmlProduct3 = $converter->makeXmlByProduct($productsData[3]['model'], false);
        $expectedXmlProduct31 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10003">
    <shop-indices>2</shop-indices>
    <name>Продукт 3</name>
    <bar-code code="7770000000003">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductPieceEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
</good>
EOF;
        $expectedXmlProduct32 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10003">
    <shop-indices>1 3</shop-indices>
    <name>Продукт 3</name>
    <bar-code code="7770000000003">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductPieceEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct31, $xmlProduct3[0]);
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct32, $xmlProduct3[1]);


        $xmlProduct4 = $converter->makeXmlByProduct($productsData[4]['model'], false);
        $this->assertEmpty($xmlProduct4);


        $xmlProduct5 = $converter->makeXmlByProduct($productsData[5]['model'], false);
        $expectedXmlProduct5 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10005">
    <shop-indices>1 2 3</shop-indices>
    <name>Виски 365 Дней</name>
    <bar-code code="7770000000005">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductSpiritsEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <plugin-property key="alcoholic-content-percentage" value="38.5"/>
    <plugin-property key="volume" value="0.375"/>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct5, $xmlProduct5[0]);


        $xmlProduct6 = $converter->makeXmlByProduct($productsData[6]['model'], false);
        $expectedXmlProduct6 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10006">
    <shop-indices>1 2 3</shop-indices>
    <name>Продукт 6</name>
    <bar-code code="7770000000006">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
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
    <product-type>ProductPieceEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <measure-type id="unit">
        <name>шт</name>
    </measure-type>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXmlProduct6, $xmlProduct6[0]);

    }

    public function testBarcodesExport()
    {
        $this->factory()->store()->getStores(array('666', '777', '888'));
        $productData = array(
            'name' => 'Продукт 1',
            'barcode' => '7770000000001',
            'vat' => '10',
            'type' => WeightType::TYPE,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'purchasePrice' => '44.11',
            'retailMarkupMin' => '40',
            'retailMarkupMax' => '60',
            'retailPricePreference' => 'retailMarkup',
            'typeProperties' => array(
                'nameOnScales' => 'Название на весах',
                'descriptionOnScales' => "Описание\nна весах",
                'ingredients' => '<масло,соль,перец>',
                'shelfLife' => '23',
                'nutritionFacts' => '"Углеводы - 12гр"'
            )
        );
        $productId = $this->createProduct($productData);

        $barcodesData = array(
            array('barcode' => '888001', 'quantity' => 10, 'price' => 69.95),
            array('barcode' => '888002', 'quantity' => 1, 'price' => ''),
            array('barcode' => '888003', 'quantity' => 2.687, 'price' => ''),
        );
        $this->updateProductBarcodes($productId, $barcodesData);

        /* @var Product $product */
        $product = $this->getProductRepository()->find($productId);

        $actualXml = $this->getConverter()->makeXmlByProduct($product, false);

        $expectedXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<good marking-of-the-good="10001">
    <shop-indices>666 777 888</shop-indices>
    <name>Продукт 1</name>
    <bar-code code="7770000000001">
        <count>1</count>
        <default-code>true</default-code>
    </bar-code>
    <bar-code code="888001">
        <count>10</count>
        <default-code>false</default-code>
        <price-entry price="69.95">
            <number>1</number>
            <department number="1">
                <name>1</name>
            </department>
        </price-entry>
    </bar-code>
    <bar-code code="888002">
        <count>1</count>
        <default-code>false</default-code>
    </bar-code>
    <bar-code code="888003">
        <count>2.687</count>
        <default-code>false</default-code>
    </bar-code>
    <price-entry price="70.58">
        <number>1</number>
        <department number="1">
            <name>1</name>
        </department>
    </price-entry>
    <vat>10</vat>
    <group id="Водка">
        <name>Водка</name>
        <parent-group id="Винно-водочные изделия">
            <name>Винно-водочные изделия</name>
            <parent-group id="Продовольственные товары">
                <name>Продовольственные товары</name>
            </parent-group>
        </parent-group>
    </group>
    <product-type>ProductWeightEntity</product-type>
    <plugin-property key="precision" value="0.001"/>
    <plugin-property key="name-on-scale-screen" value="Название на весах"/>
    <plugin-property key="description-on-scale-screen" value="Описание&#10;на весах"/>
    <plugin-property key="composition" value="&lt;масло,соль,перец&gt;"/>
    <plugin-property key="food-value" value="&quot;Углеводы - 12гр&quot;"/>
    <plugin-property key="good-for-hours" value="23"/>
    <measure-type id="kg">
        <name>кг</name>
    </measure-type>
</good>
EOF;
        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml[0]);
    }


    public function testWriteRemoteFile()
    {
        $products = $this->initBase();

        $barcodesData = array(
            array('barcode' => '888001', 'quantity' => 10, 'price' => 69.95),
            array('barcode' => '888002', 'quantity' => 1, 'price' => ''),
            array('barcode' => '888003', 'quantity' => 2.687, 'price' => ''),
        );
        $this->updateProductBarcodes($products[6]['model']->id, $barcodesData);

        $xmlFilePath = "/tmp/lighthouse_unit_test";
        /* @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get('filesystem');
        if ($filesystem->exists($xmlFilePath)) {
            $filesystem->remove($xmlFilePath);
        }
        mkdir($xmlFilePath . "/source", 0777, true);
        $xmlFileUrl = "file://" . $xmlFilePath;

        $this->createConfig(Set10Export::URL_CONFIG_NAME, $xmlFileUrl);

        $commercialAccessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/integration/export/products'
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

        $this->getDocumentManager()->clear();

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
        /** @var ExportProductsWorker $worker */
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
        $this->authenticateProject();

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

    public function testProductBarcodeUpdatesDoesNotExport()
    {
        $products = $this->initBase();

        $barcodesData = array(
            array('barcode' => '888001', 'quantity' => 10, 'price' => 69.95),
            array('barcode' => '888002', 'quantity' => 1, 'price' => ''),
            array('barcode' => '888003', 'quantity' => 2.687, 'price' => ''),
        );
        $this->updateProductBarcodes($products[6]['model']->id, $barcodesData);

        $xmlFilePath = "/tmp/lighthouse_unit_test_" . uniqid();
        /* @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get('filesystem');
        if ($filesystem->exists($xmlFilePath)) {
            $filesystem->remove($xmlFilePath);
        }

        mkdir($xmlFilePath . "/source", 0777, true);
        $xmlFileUrl = "file://" . $xmlFilePath;

        $this->createConfig(Set10Export::URL_CONFIG_NAME, $xmlFileUrl);

        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');

        $jobManager->addJob(new ExportProductsJob());

        $jobManager->startWatchingTubes();

        $job = $jobManager->reserveJob(0);
        $jobManager->processJob($job);

        $files = glob($xmlFilePath . "/source/*");
        $this->assertXmlFileEqualsXmlFile(
            $this->getFixtureFilePath('Integration/Set10/Export/Products/ExportProducts.xml'),
            array_pop($files)
        );

        $barcodesData = array(
            array('barcode' => '888001', 'quantity' => 10, 'price' => 69.95),
            array('barcode' => '888002', 'quantity' => 1, 'price' => ''),
            array('barcode' => '888003', 'quantity' => 2.600, 'price' => ''),
        );
        $this->updateProductBarcodes($products[6]['model']->id, $barcodesData);
        $jobManager->addJob(new ExportProductsJob());

        $job = $jobManager->reserveJob(0);
        $jobManager->processJob($job);

        $jobManager->stopWatchingTubes();

        $files = glob($xmlFilePath . "/source/*");
        $this->assertXmlFileEqualsXmlFile(
            $this->getFixtureFilePath('Integration/Set10/Export/Products/ExportProductsUpdated.xml'),
            array_pop($files)
        );
    }
}
