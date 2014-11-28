<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\InvoiceBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InvoiceControllerTest extends WebTestCase
{
    /**
     * @param string $supplierId
     * @param string $productId
     * @param string|float $quantity
     * @param string|float $price
     * @return array
     */
    public static function getInvoiceData($supplierId, $productId, $quantity = '10', $price = '5.99')
    {
        return self::getStoreInvoiceData(null, $supplierId, $productId, $quantity, $price);
    }

    /**
     * @param string $storeId
     * @param string $supplierId
     * @param string $productId
     * @param string|float $quantity
     * @param string|float $price
     * @return array
     */
    public static function getStoreInvoiceData($storeId, $supplierId, $productId, $quantity = '10', $price = '5.99')
    {
        return InvoiceBuilder::create($storeId, '2013-03-18 12:56', $supplierId)
            ->addProduct($productId, $quantity, $price)
            ->toArray();
    }

    public function testStorePostInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $supplier = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $invoiceData = InvoiceBuilder::create(null, '2013-03-18 12:56:00', $supplier->id)
                ->setAccepter('Приемных Н.П.')
                ->setSupplierInvoiceNumber('1248373')
                ->setLegalEntity('ООО "Магазин"')
                ->addProduct($product->id)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $postResponse);
        Assert::assertJsonPathEquals('ООО "Поставщик"', 'supplier.name', $postResponse);
        Assert::assertJsonPathEquals('2013-03-18T12:56:00+0400', 'date', $postResponse);
        Assert::assertJsonPathEquals('Приемных Н.П.', 'accepter', $postResponse);
        Assert::assertJsonPathEquals('ООО "Магазин"', 'legalEntity', $postResponse);
        Assert::assertJsonPathEquals('1248373', 'supplierInvoiceNumber', $postResponse);
        Assert::assertJsonPathEquals(true, 'includesVAT', $postResponse);
    }

    public function testPostInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $supplier = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $invoiceData = InvoiceBuilder::create($store->id, '2013-03-18 12:56:00', $supplier->id)
                ->setAccepter('Приемных Н.П.')
                ->setSupplierInvoiceNumber('1248373')
                ->setLegalEntity('ООО "Магазин"')
                ->addProduct($product->id)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $postResponse);
        Assert::assertJsonPathEquals('ООО "Поставщик"', 'supplier.name', $postResponse);
        Assert::assertJsonPathEquals('2013-03-18T12:56:00+0400', 'date', $postResponse);
        Assert::assertJsonPathEquals('Приемных Н.П.', 'accepter', $postResponse);
        Assert::assertJsonPathEquals('ООО "Магазин"', 'legalEntity', $postResponse);
        Assert::assertJsonPathEquals('1248373', 'supplierInvoiceNumber', $postResponse);
        Assert::assertJsonPathEquals(true, 'includesVAT', $postResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/invoices/' . $postResponse['id']
        );

        $this->assertResponseCode(200);

        $this->assertEquals($postResponse, $getResponse);
    }

    public function testGetInvoicesAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        for ($i = 0; $i < 5; $i++) {
            $this->factory()
                ->invoice()
                    ->createInvoice(array(), $store->id)
                    ->createInvoiceProduct($product->id, 10, 5.99)
                ->flush();
        }

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $getResponse);
    }

    public function testGetInvoicesActionMaxDepth()
    {
        $store = $this->factory()->store()->getStore();
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1']->id, 9, 9.99)
                ->createInvoiceProduct($products['2']->id, 19, 19.99)
            ->flush()->id;

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1']->id, 119, 9.99)
                ->createInvoiceProduct($products['2']->id, 129, 19.99)
                ->createInvoiceProduct($products['3']->id, 139, 19.99)
            ->flush()->id;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(0, '0.store.departmentManagers.*', $getResponse);
        Assert::assertJsonPathCount(0, '0.store.storeManagers.*', $getResponse);
        Assert::assertJsonHasPath('0.products.0.product', $getResponse);
        Assert::assertJsonHasPath('0.products.1.product', $getResponse);
        Assert::assertNotJsonHasPath('0.products.0.product.subCategory', $getResponse);
        Assert::assertNotJsonHasPath('0.products.1.product.subCategory', $getResponse);
    }

    /**
     * @dataProvider postInvoiceDataProvider
     * @param array $invoiceData
     * @param array $assertions
     */
    public function testGetInvoice(array $invoiceData, array $assertions)
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $invoice = $this->factory()
            ->invoice()
                ->createInvoice($invoiceData, $store->id)
                ->createInvoiceProduct($product->id, 10, 5.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($getResponse, $assertions, true);
    }

    public function postInvoiceDataProvider()
    {
        return array(
            'invoice' => array(
                'data' => array(
                    'date' => '2013-03-18 12:56',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceNumber' => '1248373',
                ),
                // Assertions xpath
                'assertions' => array(
                    'number' => '10001',
                    'date' => '2013-03-18T12:56:00+0400',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceNumber' => '1248373'
                )
            )
        );
    }

    public function testGetInvoiceNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $id = 'not_exists_id';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $id
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('41');
        $store2 = $this->factory()->store()->getStore('43');
        $product = $this->factory()->catalog()->getProduct();
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($product->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->auth($departmentManager);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider validateProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $supplier = $this->factory()->supplier()->getSupplier();

        $postData = $data + $this->getInvoiceData($supplier->id, $product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            if (is_bool($expected)) {
                Assert::assertJsonPathEquals($expected, $path, $postResponse);
            } else {
                Assert::assertJsonPathContains($expected, $path, $postResponse);
            }
        }
    }

    /**
     * @dataProvider providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid
     * @param array $data
     */
    public function testPostInvoiceSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid(array $data)
    {
        $store = $this->factory()->store()->getStore();
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $postData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Вы ввели неверную дату',
            'errors.children.date.errors.0',
            $postResponse
        );
        Assert::assertNotJsonHasPath('errors.children.supplierInvoiceDate.errors.0', $postResponse);
    }

    /**
     * @return array
     */
    public function providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid()
    {
        return array(
            'supplierInvoiceDate in past' => array(
                array(
                    'date' => 'aaa',
                    'supplierInvoiceDate' => '2012-03-14'
                ),
            ),
            'supplierInvoiceDate in future' => array(
                array(
                    'date' => 'aaa',
                    'supplierInvoiceDate' => '2015-03-14'
                ),
            )
        );
    }

    public function testStorePutInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $supplier1 = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $supplier2 = $this->factory()->supplier()->getSupplier('ООО "Подставщик"');

        $postData = InvoiceBuilder::create(null, '2013-03-18 12:56:00', $supplier1->id)
                ->setAccepter('Приемных Н.П.')
                ->setSupplierInvoiceNumber('1248373')
                ->setLegalEntity('ООО "Магазин"')
                ->addProduct($product->id, 10, 5.99)
            ->toArray();

        $assertions = array(
            'number' => '10001',
            'supplier.name' => 'ООО "Поставщик"',
            'date' => '2013-03-18T12:56:00+0400',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];

        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $postJson);
        }

        $postData['supplier'] = $supplier2->id;
        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $postData
        );

        $assertions['supplier.name'] = 'ООО "Подставщик"';
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceId, 'id', $postJson);
        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $putJson);
        }
    }

    public function testPutInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProduct();
        $supplier1 = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $supplier2 = $this->factory()->supplier()->getSupplier('ООО "Подставщик"');

        $postData = InvoiceBuilder::create($store->id, '2013-03-18 12:56:00', $supplier1->id)
                ->setAccepter('Приемных Н.П.')
                ->setSupplierInvoiceNumber('1248373')
                ->setLegalEntity('ООО "Магазин"')
                ->addProduct($product->id, 10, 5.99);

        $assertions = array(
            'number' => '10001',
            'supplier.name' => 'ООО "Поставщик"',
            'date' => '2013-03-18T12:56:00+0400',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $postData->toArray()
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];

        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $postJson);
        }

        $postData->setSupplier($supplier2->id);

        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$invoiceId}",
            $postData->toArray()
        );

        $assertions['supplier.name'] = 'ООО "Подставщик"';
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceId, 'id', $postJson);
        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $putJson);
        }
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'number'
             ***********************************************************************************************/
            'number is skipped' => array(
                201,
                array('number' => '10001'),
            ),
            'valid number 100 chars' => array(
                201,
                array('number' => str_repeat('z', 100)),
            ),
            /***********************************************************************************************
             * 'order'
             ***********************************************************************************************/
            'invalid order' => array(
                400,
                array('order' => '10001'),
                array('errors.children.order.errors.0' => 'Такой заказ не существует'),
            ),
            'empty order' => array(
                201,
                array('order' => ''),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            /*
            'supplier is empty' => array(
                400,
                array('supplier' => ''),
                array('errors.children.supplier.errors.0' => 'Выберите поставщика'),
            ),
            */
            'supplier is invalid' => array(
                400,
                array('supplier' => 'aaaa'),
                array('errors.children.supplier.errors.0' => 'Такого поставщика не существует'),
            ),
            'supplier is invalid object' => array(
                400,
                array('supplier' => array('id' => 'aaaa', 'name' => 'ООО "Поставщик"')),
                array('errors.children.supplier.errors.0' => 'Такого поставщика не существует'),
            ),
            /***********************************************************************************************
             * 'accepter'
             ***********************************************************************************************/
            'valid accepter' => array(
                201,
                array('accepter' => 'accepter'),
            ),
            'valid accepter 100 chars' => array(
                201,
                array('accepter' => str_repeat('z', 100)),
            ),
            /*
            'empty accepter' => array(
                400,
                array('accepter' => ''),
                array('errors.children.accepter.errors.0' => 'Заполните это поле',),
            ),
            */
            'not valid accepter too long' => array(
                400,
                array('accepter' => str_repeat("z", 105)),
                array('errors.children.accepter.errors.0' => 'Не более 100 символов'),
            ),
            /***********************************************************************************************
             * 'legalEntity'
             ***********************************************************************************************/
            'valid legalEntity' => array(
                201,
                array('legalEntity' => 'legalEntity'),
            ),
            'valid legalEntity 300 chars' => array(
                201,
                array('legalEntity' => str_repeat('z', 300)),
            ),
            /*
            'empty legalEntity' => array(
                400,
                array('legalEntity' => ''),
                array('errors.children.legalEntity.errors.0' => 'Заполните это поле'),
            ),
            */
            'not valid legalEntity too long' => array(
                400,
                array('legalEntity' => str_repeat("z", 305)),
                array('errors.children.legalEntity.errors.0' => 'Не более 300 символов'),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceNumber'
             ***********************************************************************************************/
            'valid supplierInvoiceNumber' => array(
                201,
                array('supplierInvoiceNumber' => 'supplierInvoiceNumber'),
            ),
            'valid supplierInvoiceNumber 100 chars' => array(
                201,
                array('supplierInvoiceNumber' => str_repeat('z', 100)),
            ),
            /*
            'empty supplierInvoiceNumber' => array(
                400,
                array('supplierInvoiceNumber' => ''),
                array('errors.children.supplierInvoiceNumber.errors.0' => 'Заполните это поле'),
            ),
            */
            'not valid supplierInvoiceNumber too long' => array(
                400,
                array('supplierInvoiceNumber' => str_repeat("z", 105)),
                array('errors.children.supplierInvoiceNumber.errors.0' => 'Не более 100 символов'),
            ),
            /***********************************************************************************************
             * 'date'
             ***********************************************************************************************/
            'valid acceptanceDate 2013-03-26T12:34:56' => array(
                201,
                array('date' => '2013-03-26T12:34:56'),
                array('date' => '2013-03-26T12:34:56+0400')
            ),
            'valid acceptanceDate 2013-03-26' => array(
                201,
                array('date' => '2013-03-26'),
                array('date' => '2013-03-26T00:00:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34' => array(
                201,
                array('date' => '2013-03-26 12:34'),
                array('date' => '2013-03-26T12:34:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34:45' => array(
                201,
                array('date' => '2013-03-26 12:34:45'),
                array('date' => '2013-03-26T12:34:45+0400')
            ),
            'empty acceptanceDate' => array(
                400,
                array('date' => ''),
                array('errors.children.date.errors.0' => 'Заполните это поле'),
            ),
            'not valid acceptanceDate 2013-02-31' => array(
                400,
                array('date' => '2013-02-31'),
                array('errors.children.date.errors.0' => 'Вы ввели неверную дату'),
            ),
            'not valid acceptanceDate aaa' => array(
                400,
                array('date' => 'aaa'),
                array('errors.children.date.errors.0' => 'Вы ввели неверную дату',),
            ),
            'not valid acceptanceDate __.__.____ __:__' => array(
                400,
                array('date' => '__.__.____ __:__'),
                array('errors.children.date.errors.0' => 'Вы ввели неверную дату',),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceDate'
             ***********************************************************************************************/
            'supplierInvoiceDate should not be present' => array(
                400,
                array('supplierInvoiceDate' => '2013-02-31'),
                array(
                    'errors.errors.0'
                    =>
                    'Эта форма не должна содержать дополнительных полей: "supplierInvoiceDate"',
                ),
            ),
            /***********************************************************************************************
             * 'createdDate'
             ***********************************************************************************************/
            'not valid createdDate' => array(
                400,
                array('createdDate' => '2013-03-26T12:34:56'),
                array('errors.errors.0' => 'Эта форма не должна содержать дополнительных полей'),
            ),
            /***********************************************************************************************
             * 'products'
             ***********************************************************************************************/
            'empty products' => array(
                400,
                array('products' => array()),
                array('errors.errors.0' => 'Нужно добавить минимум один товар'),
            ),
            /***********************************************************************************************
             * 'paid'
             ***********************************************************************************************/
            'paid true' => array(
                201,
                array('paid' => true),
                array('paid' => true),
            ),
            'paid false' => array(
                201,
                array('paid' => false),
                array('paid' => false),
            ),
            'paid empty becomes true and it is weird' => array(
                201,
                array('paid' => ''),
                array('paid' => true),
            ),
            'paid null' => array(
                201,
                array('paid' => null),
                array('paid' => false),
            ),
            'paid aaa' => array(
                201,
                array('paid' => 'aaa'),
                array('paid' => true),
            ),
        );
    }

    public function testDepartmentManagerCantGetInvoiceFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('41');
        $store2 = $this->factory()->store()->getStore('43');
        $product = $this->factory()->catalog()->getProduct();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($product->id)
            ->flush();
        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store2->id)
                ->createInvoiceProduct($product->id)
            ->flush();

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store1->id}/invoices/{$invoice1->id}"
        );

        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store2->id}/invoices/{$invoice2->id}"
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/invoices/{$invoice1->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store2->id . '/invoices/' . $invoice2->id
        );

        $this->assertResponseCode(200);
    }

    /**
     * @param string $query
     * @param int $count
     * @param array $assertions
     *
     * @dataProvider invoiceFilterProvider
     */
    public function testInvoicesFilter($query, $count, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProductByName('111');
        $productId2 = $this->createProductByName('222');
        $productId3 = $this->createProductByName('333');

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => 'ФРГ-1945'), $store->id)
                ->createInvoiceProduct($productId1, 10, 6.98, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => '10001'), $store->id)
                ->createInvoiceProduct($productId2, 5, 10.12, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => '10003'), $store->id)
                ->createInvoiceProduct($productId3, 7, 67.32, $store->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => $query)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount($count, '*.id', $response);
        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function invoiceFilterProvider()
    {
        return array(
            'one by number' => array(
                '10002',
                1,
                array(
                    '0.number' => '10002',
                    '0._meta.highlights.number' => true,
                )
            ),
            'one by supplierInvoiceNumber' => array(
                'ФРГ-1945',
                1,
                array(
                    '0.supplierInvoiceNumber' => 'ФРГ-1945',
                    '0._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'one by both number and supplierInvoiceNumber' => array(
                '10003',
                1,
                array(
                    '0.supplierInvoiceNumber' => '10003',
                    '0.number' => '10003',
                    '0._meta.highlights.number' => true,
                    '0._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'none found: not existing number' => array(
                '1234',
                0,
            ),
            'none found: empty number' => array(
                '',
                0,
            ),
            'none found: partial number' => array(
                '1000',
                0,
            ),
            'two: one by number and one by supplierInvoiceNumber' => array(
                '10001',
                2,
                array(
                    '0.number' => '10001',
                    '1.supplierInvoiceNumber' => '10001',
                    '0._meta.highlights.number' => true,
                    '1._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'one by number check invoice products' => array(
                '10002',
                1,
                array(
                    '0.number' => '10002',
                    '0._meta.highlights.number' => true,
                )
            ),
        );
    }

    public function testInvoicesFilterOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProductByName('111');
        $productId2 = $this->createProductByName('222');

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => 'ФРГ-1945',
                        'date' => '2013-03-17T16:12:33+0400',
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId1, 10, 6.98)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => '10001',
                        'date' => '2013-03-16T14:54:23+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId2, 5, 10.12)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => '10001')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('10001', '0.number', $response);
        Assert::assertJsonPathEquals('10002', '1.number', $response);

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => 'ФРГ-1945',
                        'date' => '2013-03-15T16:12:33+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId1, 10, 6.98, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => '10003',
                        'date' => '2013-03-16T14:54:23+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId2, 5, 10.12, $store->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => '10003')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('10004', '0.number', $response);
        Assert::assertJsonPathEquals('10003', '1.number', $response);
    }

    public function testInvoiceWithVATFields()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId1 = $this->createProduct(array('vat' => '10', 'barcode' => '111'));
        $productId2 = $this->createProduct(array('vat' => '18', 'barcode' => '222'));

        $invoiceData = $this->getInvoiceData($supplier->id, $productId1, 99.99, 36.78);
        $invoiceData['includesVAT'] = true;
        $invoiceData['products'][1] = array(
            'product' => $productId2,
            'quantity' => 10.77,
            'priceEntered' => 6.98
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3752.8, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3407.42, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(345.39, 'totalAmountVAT', $response);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);
    }

    /**
     * Проверяем что указав цену без НДС получим данные соответствующие данным теста выше
     */
    public function testInvoiceWithVATFieldsWithoutVAT()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId1 = $this->createProduct(array('vat' => '10', 'barcode' => '111'));
        $productId2 = $this->createProduct(array('vat' => '18', 'barcode' => '222'));

        $invoiceData = $this->getInvoiceData($supplier->id, $productId1, 99.99, 33.44);
        $invoiceData['includesVAT'] = false;
        $invoiceData['products'][1] = array(
            'product' => $productId2,
            'quantity' => 10.77,
            'priceEntered' => 5.92
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3752.8, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3407.42, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(345.39, 'totalAmountVAT', $response);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);
    }

    public function testInvoiceChangeIncludesVAT()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('vat' => '10', 'barcode' => '111'));
        $this->createProduct(array('vat' => '18', 'barcode' => '222'));
        $supplier = $this->factory()->supplier()->getSupplier();

        $invoiceData1 = array(
            'supplier' => $supplier->id,
            'date' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
            'includesVAT' => true,
            'products' => array(
                array(
                    'product' => $productId1,
                    'quantity' => '99.99',
                    'priceEntered' => '36.78'
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData1
        );

        $this->assertResponseCode(201);

        $invoiceId1 = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);

        $invoiceData1['includesVAT'] = false;

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1,
            $invoiceData1
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $response);
    }

    public function testProductSubCategoryIsNotExposed()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1']->id, 2, 9.99, $store)
                ->createInvoiceProduct($products['2']->id, 3, 4.99, $store)
                ->createInvoiceProduct($products['3']->id, 2, 1.95, $store)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices/{$invoice->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $getResponse);
    }

    public function testValidationGroup()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProductByName('1');

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 5.99);
        $invoiceData['date'] = '';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Заполните это поле', 'errors.children.date.errors.0', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Заполните это поле', 'errors.children.date.errors.0', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertNotJsonHasPath('errors.children.date.errors.0', $response);
        Assert::assertNotJsonHasPath('acceptanceData', $response);
        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals('59.90', 'sumTotal', $response);

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertNotJsonHasPath('errors.children.date.errors.0', $response);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    /**
     * @dataProvider validationGroupDataProvider
     * @param array $invalidData
     * @param string $expectedErrorPath
     * @param string $expectedErrorMessage
     * @param $expectedEmptyField
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testValidationGroupWithInvalidSupplier(
        array $invalidData,
        $expectedErrorPath,
        $expectedErrorMessage,
        $expectedEmptyField
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProductByName('1');

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 5.99);
        $invoiceData = $invalidData + $invoiceData;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals($expectedErrorMessage, $expectedErrorPath, $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals($expectedErrorMessage, $expectedErrorPath, $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertNotJsonHasPath($expectedErrorPath, $response);
        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals('59.90', 'sumTotal', $response);
        Assert::assertNotJsonHasPath($expectedEmptyField, $response);

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertNotJsonHasPath($expectedErrorPath, $response);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function validationGroupDataProvider()
    {
        return array(
            'not found supplier' => array(
                array('supplier' => 'aaaa'),
                'errors.children.supplier.errors.0',
                'Такого поставщика не существует',
                'supplier'
            ),
            /*
            'empty supplier' => array(
                array('supplier' => ''),
                'errors.children.supplier.errors.0',
                'Выберите поставщика',
                'supplier'
            ),
            */
            'empty acceptance date' => array(
                array('date' => ''),
                'errors.children.date.errors.0',
                'Заполните это поле',
                'date'
            ),
            /*
            'invalid acceptance date' => array(
                array('date' => 'aaaa'),
                'errors.children.date.errors.0',
                'Заполните это поле',
                'date'
            )
            */
        );
    }

    /**
     * @dataProvider totalsCalculationOnPostWithValidationGroupDataProvider
     * @param int $VAT
     * @param bool $includesVAT
     * @param float $quantity
     * @param float $price
     * @param float $expectedSumTotal
     * @param float $expectedSumTotalWithoutVAT
     * @param float $expectedTotalAmountVAT
     * @param float $expectedPrice
     * @param float $expectedPriceWithoutVAT
     * @param float $expectedAmountVAT
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPost(
        $VAT,
        $includesVAT,
        $quantity,
        $price,
        $expectedSumTotal,
        $expectedSumTotalWithoutVAT,
        $expectedTotalAmountVAT,
        $expectedPrice,
        $expectedPriceWithoutVAT,
        $expectedAmountVAT
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct(array('vat' => $VAT));

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, $quantity, $price);
        $invoiceData['includesVAT'] = $includesVAT;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );
        $this->assertResponseCode(201);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($expectedSumTotal, 'sumTotal', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'products.0.totalPrice', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'products.0.totalPriceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'products.0.totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedPrice, 'products.0.price', $response);
        Assert::assertJsonPathEquals($expectedPriceWithoutVAT, 'products.0.priceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedAmountVAT, 'products.0.amountVAT', $response);
    }

    /**
     * @dataProvider totalsCalculationOnPostWithValidationGroupDataProviderOnlyIncludesVat
     * @param int $VAT
     * @param bool $includesVAT
     * @param float $quantity
     * @param float $price
     * @param float $expectedSumTotal
     * @param float $expectedSumTotalWithoutVAT
     * @param float $expectedTotalAmountVAT
     * @param float $expectedPrice
     * @param float $expectedPriceWithoutVAT
     * @param float $expectedAmountVAT
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPostOnlyProducts(
        $VAT,
        $includesVAT,
        $quantity,
        $price,
        $expectedSumTotal,
        $expectedSumTotalWithoutVAT,
        $expectedTotalAmountVAT,
        $expectedPrice,
        $expectedPriceWithoutVAT,
        $expectedAmountVAT
    ) {
        $this->factory()->store()->getStore();
        $this->factory()->supplier()->getSupplier();

        $productId = $this->createProduct(array('vat' => $VAT));

        $invoiceData = array(
            'products' => array(
                array(
                    'quantity' => $quantity,
                    'priceEntered' => $price,
                    'product' => $productId,
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices?validate=true&validationGroups=products',
            $invoiceData
        );
        $this->assertResponseCode(201);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'sumTotal', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'products.0.totalPrice', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'products.0.totalPriceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'products.0.totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedPrice, 'products.0.price', $response);
        Assert::assertJsonPathEquals($expectedPriceWithoutVAT, 'products.0.priceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedAmountVAT, 'products.0.amountVAT', $response);
    }

    /**
     * @dataProvider totalsCalculationOnPostWithValidationGroupDataProvider
     * @param int $VAT
     * @param bool $includesVAT
     * @param float $quantity
     * @param float $price
     * @param float $expectedSumTotal
     * @param float $expectedSumTotalWithoutVAT
     * @param float $expectedTotalAmountVAT
     * @param $expectedPrice
     * @param $expectedPriceWithoutVAT
     * @param $expectedAmountVAT
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPut(
        $VAT,
        $includesVAT,
        $quantity,
        $price,
        $expectedSumTotal,
        $expectedSumTotalWithoutVAT,
        $expectedTotalAmountVAT,
        $expectedPrice,
        $expectedPriceWithoutVAT,
        $expectedAmountVAT
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct(array('vat' => $VAT));

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, '5', '5.00');
        $invoiceData['includesVAT'] = $includesVAT;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $invoiceData['products'][0]['quantity'] = $quantity;
        $invoiceData['products'][0]['priceEntered'] = $price;
        $invoiceData['includesVAT'] = $includesVAT;

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId . '?validate=true&validationGroups=products',
            $invoiceData
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($invoiceId, 'id', $response);
        Assert::assertJsonPathEquals($expectedSumTotal, 'sumTotal', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'products.0.totalPrice', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'products.0.totalPriceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'products.0.totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedPrice, 'products.0.price', $response);
        Assert::assertJsonPathEquals($expectedPriceWithoutVAT, 'products.0.priceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedAmountVAT, 'products.0.amountVAT', $response);
    }

    /**
     * @return array
     */
    public function totalsCalculationOnPostWithValidationGroupDataProvider()
    {
        return array(
            'with VAT 10%' => array(
                'VAT' => 10,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '54.40',
                'expectedTotalAmountVAT' => '5.50',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.44',
                'expectedAmountVAT' => '0.55',
            ),
            'without VAT 10%' => array(
                'VAT' => 10,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '65.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '6.00',
                'expectedPrice' => '6.59',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.60',
            ),
            'with VAT 18%' => array(
                'VAT' => 18,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '50.80',
                'expectedTotalAmountVAT' => '9.10',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.08',
                'expectedAmountVAT' => '0.91',
            ),
            'without VAT 18%' => array(
                'VAT' => 18,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '70.70',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '10.80',
                'expectedPrice' => '7.07',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '1.08',
            ),
            'with VAT 0%' => array(
                'VAT' => 0,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '0.00',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.00',
            ),
            'without VAT 0%' => array(
                'VAT' => 0,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '0.00',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.00',
            ),
        );
    }

    /**
     * @return array
     */
    public function totalsCalculationOnPostWithValidationGroupDataProviderOnlyIncludesVat()
    {
        return array_filter(
            $this->totalsCalculationOnPostWithValidationGroupDataProvider(),
            function ($data) {
                return $data['includesVAT'];
            }
        );
    }

    public function testProductsBecomeEmptyOnPutOnAcceptanceDateChange()
    {
        $store = $this->factory()->store()->createStore();
        $product = $this->factory()->catalog()->getProduct();
        $supplier = $this->factory()->supplier()->getSupplier();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonPathCount(1, 'products.*', $postResponse);
        $invoiceId = $postResponse['id'];

        $invoiceData['date'] = '16.04.2014 15:09';
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, 'products.*', $putResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}"
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, 'products.*', $getResponse);
        $this->assertSame($putResponse['products'][0], $getResponse['products'][0]);
    }

    public function testGetInvoiceDataByOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'barcode' => '1'));
        $productId2 = $this->createProduct(array('purchasePrice' => '22.22', 'name' => 'Продукт 2', 'barcode' => '2'));
        $productId3 = $this->createProduct(array('purchasePrice' => '33.33', 'name' => 'Продукт 3', 'barcode' => '3'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()
            ->order()
                ->createOrder($store, $supplier, '2014-04-16 17:39')
                ->createOrderProduct($productId1, 11)
                ->createOrderProduct($productId2, 22)
                ->createOrderProduct($productId3, 33)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id . '/invoice'
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $response);
        Assert::assertJsonPathCount('3', 'products.*', $response);

        Assert::assertJsonPathEquals($productId1, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('11', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('11.11', 'products.0.price', $response);

        Assert::assertJsonPathEquals($productId2, 'products.1.product.id', $response);
        Assert::assertJsonPathEquals('22', 'products.1.quantity', $response);
        Assert::assertJsonPathEquals('22.22', 'products.1.price', $response);

        Assert::assertJsonPathEquals($productId3, 'products.2.product.id', $response);
        Assert::assertJsonPathEquals('33', 'products.2.quantity', $response);
        Assert::assertJsonPathEquals('33.33', 'products.2.price', $response);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertNotJsonHasPath('number', $response);
        Assert::assertNotJsonHasPath('legalEntity', $response);
        Assert::assertNotJsonHasPath('accepter', $response);
        Assert::assertNotJsonHasPath('date', $response);
        Assert::assertNotJsonHasPath('supplierInvoiceNumber', $response);
    }

    public function testGetInvoiceDataByOrderNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId1 = $this->createProductByName('1');
        $productId2 = $this->createProductByName('2');

        $supplier = $this->factory()->supplier()->getSupplier();

        $order1 = $this->factory()
            ->order()
                ->createOrder($store1, $supplier)
                ->createOrderProduct($productId1, 11)
            ->flush();

        $order2 = $this->factory()
            ->order()
                ->createOrder($store2, $supplier)
                ->createOrderProduct($productId2, 22)
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store1->id . '/orders/' . $order2->id . '/invoice'
        );
        $this->assertResponseCode(404);

        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store2->id . '/orders/' . $order1->id . '/invoice'
        );
        $this->assertResponseCode(404);
    }

    public function testCreateInvoiceWithOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'barcode' => '1'));
        $productId2 = $this->createProduct(array('purchasePrice' => '22.22', 'name' => 'Продукт 2', 'barcode' => '2'));
        $productId3 = $this->createProduct(array('purchasePrice' => '33.33', 'name' => 'Продукт 3', 'barcode' => '3'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()
            ->order()
                ->createOrder($store, $supplier, '2014-04-16 17:39')
                ->createOrderProduct($productId1, 11)
                ->createOrderProduct($productId2, 22)
                ->createOrderProduct($productId3, 33)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($order->id, 'order.id', $response);
        Assert::assertJsonPathEquals('10001', 'number', $response);
        Assert::assertJsonPathCount(3, 'products.*', $response);
    }

    public function testDuplicateInvoiceOnOrderCreate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'barcode' => '1'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()
            ->order()
                ->createOrder($store, $supplier, '2014-04-16 17:39')
                ->createOrderProduct($productId, 11)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Накладная по этому заказу уже существует',
            'errors.children.order.errors.0',
            $response
        );
    }

    /**
     * @dataProvider exceptionOnInvoiceCreateProvider
     * @param \Exception $exception
     * @param $expectedResponseCode
     * @param array $assertions
     */
    public function testExceptionOnInvoiceCreate(
        \Exception $exception,
        $expectedResponseCode,
        array $assertions
    ) {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()
            ->order()
                ->createOrder($store, $supplier, '2014-04-16 17:39')
                ->createOrderProduct($productId, 11)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $this->mockExceptionOnCreateInvoice($exception);

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData
        );

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function exceptionOnInvoiceCreateProvider()
    {
        return array(
            'mongo duplicate exception' => array(
                new \MongoDuplicateKeyException(),
                400,
                array('errors.children.order.errors.0' => 'Накладная по этому заказу уже существует')
            ),
            'unknown exception' => array(
                new \Exception('Something went wrong'),
                500,
                array('message' => 'Something went wrong'),
            )
        );
    }

    /**
     * @param \Exception $exception
     */
    protected function mockExceptionOnCreateInvoice(\Exception $exception)
    {
        $invoice = new Invoice();
        $invoice->sumTotal = $this->getNumericFactory()->createMoney();
        $invoice->sumTotalWithoutVAT = $this->getNumericFactory()->createMoney();
        $invoice->totalAmountVAT = $this->getNumericFactory()->createMoney();

        $documentManagerMock = $this
            ->getMockBuilder('Doctrine\\ODM\\MongoDB\\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $documentManagerMock
            ->expects($this->once())
            ->method('persist');

        $documentManagerMock
            ->expects($this->once())
            ->method('flush')
            ->with($this->isEmpty())
            ->will($this->throwException($exception));

        $invoiceRepoMock = $this
            ->getMockBuilder('Lighthouse\\CoreBundle\\Document\\StockMovement\\Invoice\\InvoiceRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $invoiceRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($invoice));
        $invoiceRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($invoiceRepoMock) {
                $container->set('lighthouse.core.document.repository.stock_movement.invoice', $invoiceRepoMock);
            }
        );
    }

    public function testInvoiceIsExposedInOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'barcode' => '1'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()
            ->order()
                ->createOrder($store, $supplier, '2014-04-16 17:39')
                ->createOrderProduct($productId, 11)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($order->id, 'order.id', $response);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $response);
        Assert::assertJsonPathCount(1, 'invoice.products.*', $response);
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function getInvoiceDataByOrder(Order $order)
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($order->store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $order->store->id . '/orders/' . $order->id . '/invoice'
        );
        $this->assertResponseCode(200);
        $invoiceData = $this->getInvoiceData($response['supplier']['id'], null, null, null);
        $invoiceData['order'] = $response['order']['id'];
        foreach ($response['products'] as $key => $productData) {
            $invoiceData['products'][$key] = array(
                'product' => $productData['product']['id'],
                'quantity' => $productData['quantity'],
                'priceEntered' => $productData['priceEntered'],
            );
        }
        return $invoiceData;
    }

    public function testDeleteInvoice()
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct(array('name' => 'Продукт'));

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 10, 5.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/invoices/' . $invoice->id
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/invoices/' . $invoice->id
        );

        $this->assertResponseCode(404);

        $this->assertInvoiceDelete($invoice->id);
        $this->assertInvoiceProductDelete($invoice->products[0]->id);
    }

    /**
     * @dataProvider productTotalPriceCalculationProvider
     * @param string|float $quantity
     * @param string|float $price
     * @param string|float $totalPrice
     */
    public function testProductTotalPriceCalculation($quantity, $price, $totalPrice)
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, $quantity, $price);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $responseJson);
        Assert::assertJsonPathEquals($quantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'products.0.priceEntered', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'products.0.totalPrice', $responseJson);
        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $responseJson);
    }

    /**
     * @return array
     */
    public function productTotalPriceCalculationProvider()
    {
        return array(
            '793.32'   => array('11',       '72.12', '793.32'),
            '72.12'    => array('1',        '72.12', '72.12'),
            '216.36'   => array('3',        '72.12', '216.36'),
            '649.08'   => array('9',        '72.12', '649.08'),
            '72192.12' => array('1001',     '72.12', '72192.12'),
            '72758.99' => array('1009',     '72.11', '72758.99'),
            '72.00'    => array('1',        '72.00', '72.00'),
            '913.70'   => array('12.671',   '72.11', '913.70'),
        );
    }

    public function testPostActionNotExistingField()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id);
        $invoiceData['products'][0]['dummy'] = 'dummy';
        $invoiceData['products'][0]['foo'] = 'foo';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Эта форма не должна содержать дополнительных полей: "dummy", "foo"',
            'errors.errors.0',
            $response
        );
    }

    public function testPostActionsNotExistingProduct()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();

        $invoiceData = $this->getInvoiceData($supplier->id, 'invalid');

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Такого товара не существует',
            'errors.children.products.children.0.children.product.errors.0',
            $response
        );
    }

    public function testInvoiceTotalsAreUpdated()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $invoiceData = InvoiceBuilder::create(null, null, $supplier->id)
            ->addProduct($products[1]->id, '10', '11.12');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        $invoiceId = $response['id'];
        Assert::assertJsonPathEquals(1, 'itemsCount', $response);
        Assert::assertJsonPathEquals(111.2, 'sumTotal', $response);

        $invoiceData->addProduct($products['2']->id, 5, '12.76');

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('2', 'itemsCount', $response);
        Assert::assertJsonPathEquals('175', 'sumTotal', $response);

        $invoiceData->addProduct($products['3']->id, 1, 5.99);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('3', 'itemsCount', $response);
        Assert::assertJsonPathEquals('180.99', 'sumTotal', $response);
    }

    public function testProductAmountIsUpdatedOnInvoiceProductPost()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();

        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        // add first product
        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, '11.12');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $invoiceId = $response['id'];
        Assert::assertJsonPathEquals(1, 'itemsCount', $response);
        Assert::assertJsonPathEquals(111.2, 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $product->id, 10, '11.12');

        // Add second product
        $invoiceData['products'][1] = array(
            'product' => $product->id,
            'quantity' => 5,
            'priceEntered' => '12.76'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('2', 'itemsCount', $response);
        Assert::assertJsonPathEquals('175', 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $product->id, 15, '12.76');

        // Add third product
        $invoiceData['products'][2] = array(
            'product' => $product->id,
            'quantity' => 1,
            'priceEntered' => '5.99'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('3', 'itemsCount', $response);
        Assert::assertJsonPathEquals('180.99', 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $product->id, 16, '5.99');
    }

    /**
     * @dataProvider validationProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testStorePostActionValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();
        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 17.68);

        $invoiceData['products'][0] = $data + $invoiceData['products'][0];

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @dataProvider validationProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostActionValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();
        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 17.68);
        $invoiceData['store'] = $store->id;

        $invoiceData['products'][0] = $data + $invoiceData['products'][0];

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @dataProvider validationProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWithValidationGroup($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();
        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 17.68);
        $invoiceData['date'] = '';
        $invoiceData['products'][0] = $data + $invoiceData['products'][0];

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices?validate=true&validationGroups=products",
            $invoiceData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
        if (400 == $expectedCode) {
            Assert::assertNotJsonHasPath('errors.children.date.errors.0', $response);
        } else {
            Assert::assertNotJsonHasPath('date', $response);
        }
    }

    /**
     * @return array
     */
    public function validationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
                array('quantity' => 7),
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                201,
                array('quantity' => 2.5),
            ),
            'float quantity with coma' => array(
                201,
                array('quantity' => '2,5'),
            ),
            'float quantity very float' => array(
                400,
                array('quantity' => 2.5555),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                    'errors.children.products.children.0.children.quantity.errors.1'
                    =>
                        null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('quantity' => 'abc'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                        'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'price'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('priceEntered' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('priceEntered' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('priceEntered' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('priceEntered' => ''),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('priceEntered' => '10,898'),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('priceEntered' => '10.898'),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('priceEntered' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('priceEntered' => 'not a number'),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('priceEntered' => 0),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('priceEntered' => -10),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('priceEntered' => 2000000001),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('priceEntered' => '100000000'),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('priceEntered' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('priceEntered' => '10000001'),
                array(
                    'errors.children.products.children.0.children.priceEntered.errors.0'
                    =>
                        'Цена не должна быть больше 10000000'
                ),
            ),
            /***********************************************************************************************
             * 'product'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('product' => 'not_valid_product_id'),
                array(
                    'errors.children.products.children.0.children.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'errors.children.products.children.0.children.product.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
        );
    }

    public function testGetInvoiceProductsActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    /**
     * @dataProvider putInvoiceProvider
     * @param float $quantity
     * @param float $price
     * @param float $totalPrice
     * @param float $newQuantity
     * @param float $newPrice
     * @param float $newTotalPrice
     */
    public function testPutInvoiceProductAction($quantity, $price, $totalPrice, $newQuantity, $newPrice, $newTotalPrice)
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, $quantity, $price);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $responseJson['id'];

        Assert::assertJsonPathEquals($quantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $responseJson);

        $this->assertStoreProductTotals($store->id, $product->id, $quantity, $price);

        Assert::assertJsonPathEquals(1, 'itemsCount', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'sumTotal', $responseJson);

        $this->assertInvoiceTotals($store->id, $invoiceId, $totalPrice, 1);

        $invoiceProductId = $responseJson['products'][0]['id'];

        $invoiceData['products'][0] = array(
                'id' => $invoiceProductId,
                'quantity' => $newQuantity,
                'priceEntered' => $newPrice,
            ) + $invoiceData['products'][0];

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);
        Assert::assertNotJsonPathEquals($invoiceProductId, 'products.0.id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $responseJson);

        $this->assertStoreProductTotals($store->id, $product->id, $newQuantity, $newPrice);
        $this->assertInvoiceTotals($store->id, $invoiceId, $newTotalPrice, 1);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($newPrice, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $responseJson);
        Assert::assertJsonPathEquals($invoiceId, 'id', $responseJson);
    }

    /**
     * @return array
     */
    public function putInvoiceProvider()
    {
        return array(
            array(10, 5, 50, 20, 4, 80),
            array(6, 9.99, 59.94, 5, 9.99, 49.95)
        );
    }

    /**
     * @dataProvider putInvoiceProductActionChangeProductIdProvider
     * @param float $quantity1
     * @param float $price1
     * @param float $invoiceSumTotal1
     * @param float $quantity2
     * @param float $price2
     * @param float $invoiceSumTotal2
     */
    public function testPutInvoiceProductActionChangeProductId(
        $quantity1,
        $price1,
        $invoiceSumTotal1,
        $quantity2,
        $price2,
        $invoiceSumTotal2
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product1Id = $this->createProductByName('_1');
        $product2Id = $this->createProductByName('_2');

        $this->assertStoreProductTotals($store->id, $product1Id, 0, null);
        $this->assertStoreProductTotals($store->id, $product2Id, 0, null);

        $invoiceData = $this->getInvoiceData($supplier->id, $product1Id, $quantity1, $price1);

        // POST invoice product
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];

        $this->assertStoreProductTotals($store->id, $product1Id, $quantity1, $price1);

        Assert::assertJsonPathEquals($invoiceSumTotal1, 'sumTotal', $postJson);
        Assert::assertJsonPathEquals(1, 'itemsCount', $postJson);

        $this->assertStoreProductTotals($store->id, $product1Id, $quantity1, $price1);
        $this->assertStoreProductTotals($store->id, $product2Id, 0, null);
        $this->assertInvoiceTotals($store->id, $invoiceId, $invoiceSumTotal1, 1);

        // PUT invoice product with another product id
        $invoiceData['products'][0] = array(
                'quantity' => $quantity2,
                'priceEntered' => $price2,
                'product'  => $product2Id,
            ) + $invoiceData['products'][0];

        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($invoiceSumTotal2, 'sumTotal', $putJson);
        Assert::assertJsonPathEquals(1, 'itemsCount', $putJson);

        $this->assertStoreProductTotals($store->id, $product2Id, $quantity2, $price2);
        $this->assertStoreProductTotals($store->id, $product1Id, 0, null);
        $this->assertInvoiceTotals($store->id, $invoiceId, $invoiceSumTotal2, 1);
    }

    /**
     * @param string $storeId
     * @param string $invoiceId
     * @param string $sumTotal
     * @param int $itemsCount
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @throws \PHPUnit_Framework_Exception
     */
    protected function assertInvoiceTotals($storeId, $invoiceId, $sumTotal, $itemsCount)
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $invoiceJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        $assertions = array(
            'sumTotal' => $sumTotal,
            'itemsCount' => $itemsCount,
        );

        $this->performJsonAssertions($invoiceJson, $assertions);
    }

    /**
     * @return array
     */
    public function putInvoiceProductActionChangeProductIdProvider()
    {
        return array(
            'quantity and price changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                5, // quantity of second product
                5.99, // price of second product
                29.95, // invoice sum total
            ),
            'quantity and price are not changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                10, // quantity of second product
                9.99, // price of second product
                99.9, // invoice sum total
            ),
            'quantity changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                5, // quantity of second product
                9.99, // price of second product
                49.95, // invoice sum total
            ),
            'price changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                10, // quantity of second product
                5.99, // price of second product
                59.9, // invoice sum total
            ),
        );
    }

    public function testDeleteProductsAction()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));
        $invoiceData = InvoiceBuilder::create(null, null, $supplier->id)
            ->addProduct($products['1']->id, 1, 1.99)
            ->addProduct($products['2']->id, 2, 2.99)
            ->addProduct($products['3']->id, 3, 3.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathCount(3, 'products.*.id', $postResponse);
        Assert::assertJsonPathEquals(19.94, 'sumTotal', $postResponse);
        Assert::assertJsonPathEquals(3, 'itemsCount', $postResponse);

        $invoiceId = $postResponse['id'];

        $invoiceData->removeProduct(1);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'products.*.id', $putResponse);
        Assert::assertNotJsonPathEquals($products['2']->id, 'products.*.id', $putResponse);
        Assert::assertJsonPathEquals(13.96, 'sumTotal', $putResponse);
        Assert::assertJsonPathEquals(2, 'itemsCount', $putResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'products.*.id', $getResponse);
        Assert::assertJsonPathEquals(13.96, 'sumTotal', $getResponse);
        Assert::assertJsonPathEquals(2, 'itemsCount', $getResponse);
    }

    public function testDeleteProductsActionUpdateAmountAndInvoiceTotals()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $product->id,
            'quantity' => 5,
            'priceEntered' => 12.76,
        );
        $invoiceData['products'][2] = array(
            'product' => $product->id,
            'quantity' => 1,
            'priceEntered' => 5.99,
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $product->id, 16, 5.99);

        $this->assertInvoiceTotals($store->id, $invoiceId, 180.99, 3);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'products.*.id', $getResponse);
        Assert::assertNotJsonPathEquals(5, 'products.*.quantity', $getResponse);

        $this->assertStoreProductTotals($store->id, $product->id, 11, 5.99);
        $this->assertInvoiceTotals($store->id, $invoiceId, 117.19, 2);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductDelete()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $product->id,
            'quantity' => 5,
            'priceEntered' => 12.76
        );
        $invoiceData['products'][2] = array(
            'product' => $product->id,
            'quantity' => 1,
            'priceEntered' => 5.99
        );
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $product->id, 16, 5.99);

        unset($invoiceData['products'][2]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $product->id, 15, 12.76);

        unset($invoiceData['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $product->id, 5, 12.76);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductUpdate()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $product->id,
            'quantity' => 5,
            'priceEntered' => 12.76
        );
        $invoiceData['products'][2] = array(
            'product' => $product->id,
            'quantity' => 1,
            'priceEntered' => 5.99
        );
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $product->id, 16, 5.99);

        $invoiceData['products'][1]['priceEntered'] = 13.01;

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $product->id, 16, 5.99);

        $newProductId = $this->createProductByName('NEW');
        $invoiceData['products'][2]['product'] = $newProductId;

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $product->id, 15, 13.01);
        $this->assertStoreProductTotals($store->id, $newProductId, 1, 5.99);
    }

    public function testAveragePurchasePrice()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProductByName('1');
        $productId2 = $this->createProductByName('2');

        $invoiceData0 = $this->getInvoiceData($supplier->id, $productId1, 10, 23.33);
        $invoiceData0['date'] = date('c', strtotime('-31 days'));

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData0
        );
        $this->assertResponseCode(201);

        $invoiceData1 = $this->getInvoiceData($supplier->id, $productId1, 10, 26);
        $invoiceData1['date'] = date('c', strtotime('-3 days'));
        $invoiceData1['products'][1] = array(
            'product' => $productId2,
            'priceEntered' => 34.67,
            'quantity' => 6
        );
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData1
        );
        $this->assertResponseCode(201);
        $invoiceId1 = $response['id'];

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 26));

        $invoiceData2 = $this->getInvoiceData($supplier->id, $productId1, 5, 29);
        $invoiceData2['date'] = date('c', strtotime('-2 days'));
        $invoiceData2['products'][1] = array(
            'product' => $productId2,
            'priceEntered' => 34.67,
            'quantity' => 6
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData2
        );
        $this->assertResponseCode(201);
        $invoiceId2 = $response['id'];

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 27));

        $invoiceData3 = $this->getInvoiceData($supplier->id, $productId1, 10, 31);
        $invoiceData3['date'] = date('c', strtotime('-1 days'));
        $invoiceData3['products'][1] = array(
            'product' => $productId2,
            'priceEntered' => 34.67,
            'quantity' => 6
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData3
        );
        $this->assertResponseCode(201);
        $invoiceId3 = $response['id'];

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 28.6));

        unset($invoiceData3['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId3,
            $invoiceData3
        );

        $this->assertResponseCode(200);

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => 27, 'lastPurchasePrice' => 29)
        );

        unset($invoiceData2['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId2,
            $invoiceData2
        );

        $this->assertResponseCode(200);

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => 26, 'lastPurchasePrice' => 26)
        );

        unset($invoiceData1['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1,
            $invoiceData1
        );

        $this->assertResponseCode(200);

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => null, 'lastPurchasePrice' => 23.33)
        );
    }

    public function testAveragePurchasePriceRounded()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');

        $this->factory()
            ->invoice()
            ->createInvoice(array('date' => date('c', strtotime('-3 days'))), $store->id)
            ->createInvoiceProduct($productId1, 10, 26)
            ->createInvoiceProduct($productId2, 6, 34.67)
            ->flush();

        $this->factory()
            ->invoice()
            ->createInvoice(array('date' => date('c', strtotime('-15 days'))), $store->id)
            ->createInvoiceProduct($productId1, 10, 23.33)
            ->flush();

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 24.67));
    }

    public function testAveragePurchasePriceChangeOnInvoiceDateChange()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceBuilder = InvoiceBuilder::create(null, date('c', strtotime('now')), $supplier->id)
            ->addProduct($product->id, 10, 26);
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceBuilder->toArray()
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $product->id,
            array(
                'averagePurchasePrice' => null,
                'lastPurchasePrice' => 26
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $invoiceBuilder->setDate(date('c', strtotime('-2 days 13:00')));

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceBuilder->toArray()
        );

        $this->assertResponseCode(200);

        $this->getMetricsCalculator()->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $product->id,
            array(
                'averagePurchasePrice' => 26,
                'lastPurchasePrice' => 26
            )
        );
    }

    public function testProductDataDoesNotChangeInInvoiceAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProductByName('Кефир 1%');

        $this->assertProduct($productId, array('name' => 'Кефир 1%'));

        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
            ->createInvoiceProduct($productId, 10, 10.12)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('10001', 'products.0.product.sku', $invoiceProductsResponse);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('10001', 'products.0.product.sku', $invoiceProductsResponse);

        $this->assertProduct($productId, array('name' => 'Кефир 5%', 'sku' => '10001'));
    }

    public function testProductVersionFields()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'units' => 'л'));
        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
            ->createInvoiceProduct($productId, 10, 10.12)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('л', 'products.0.product.units', $response);
        Assert::assertJsonPathEquals('л', 'products.0.product.object.units', $response);
    }

    public function testTwoProductVersionsInInvoice()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
            ->createInvoiceProduct($productId, 10, 10.12)
            ->flush();

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $this->factory()
            ->clear()
            ->invoice()
            ->editInvoice($invoice->id)
            ->createInvoiceProduct($productId, 10, 10.12)
            ->flush();
        $this->client->shutdownKernelBeforeRequest();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'products.*.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals($productId, 'products.*.product.id', $invoiceProductsResponse, 2);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('Кефир 5%', 'products.*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('10001', 'products.*.product.sku', $invoiceProductsResponse, 2);
    }

    public function testTwoProductVersionsCreated()
    {
        $this->markTestBroken();

        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));

        /* @var VersionRepository $productVersionRepository */
        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);
        $this->assertCount(0, $productVersions);

        $productVersionRepository->clear();

        $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
            ->createInvoiceProduct($productId, 10, 10.12)
            ->flush();

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);
        $this->assertCount(1, $productVersions);
        $productVersionRepository->clear();

        // FIXME MongoTimestamp used for version createdDate sometimes is generated in wrong order
        sleep(1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);
        $this->assertCount(1, $productVersions);
        $productVersionRepository->clear();
        var_dump($productVersions->getNext()->createdDate->inc);

        $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
            ->createInvoiceProduct($productId, 5, 10.12)
            ->flush();

        $productVersionRepository->clear();

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);
        $this->assertCount(2, $productVersions);

        /* @var ProductVersion $lastVersion */
        /* @var ProductVersion $previousVersion */
        $lastVersion = $productVersions->getNext();
        $previousVersion = $productVersions->getNext();

        $this->assertEquals('Кефир 5%', $lastVersion->name);
        $this->assertEquals('Кефир 1%', $previousVersion->name);
    }

    public function testPutWithEmptyQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $product->id, 1, 9.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function testGetInvoiceProductAfterEditInvoiceAcceptanceDate()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array('date' => '2014-01-10T12:33:33+0400'), $store->id)
            ->createInvoiceProduct($product->id, 1, 9.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('2014-01-10T12:33:33+0400', 'date', $response);
        Assert::assertJsonPathEquals('2014-01-10T12:33:33+0400', 'products.0.date', $response);

        $this->factory()
            ->invoice()
            ->editInvoice($invoice->id, array('date' => '2014-01-03T10:11:10+0400'))
            ->flush();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('2014-01-03T10:11:10+0400', 'date', $response);
        Assert::assertJsonPathEquals('2014-01-03T10:11:10+0400', 'products.0.date', $response);
    }

    public function testProductsActionProductCategoryIsNotExposed()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1']->id, 2, 9.99)
                ->createInvoiceProduct($products['2']->id, 3, 4.99)
                ->createInvoiceProduct($products['3']->id, 2, 1.95)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/invoices/{$invoice->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('products.*.product.subCategory', $getResponse);
        Assert::assertNotJsonHasPath('products.*.invoice', $getResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category.group', $getResponse);
        Assert::assertJsonPathCount(0, 'products.*.invoice.products.*.id', $getResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $getResponse);
    }

    public function testPostWithDeletedSupplier()
    {
        $store = $this->factory()->store()->createStore();

        $product = $this->factory()->catalog()->getProductByName();

        $supplier = $this->factory()->supplier()->getSupplier();
        $this->factory()->supplier()->deleteSupplier($supplier);

        $invoiceData = self::getStoreInvoiceData($store->id, $supplier->id, $product->id);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $postResponse
        );
        Assert::assertJsonPathCount(0, 'errors.children.store.errors', $postResponse);
    }

    public function testPostWithDeletedStore()
    {
        $store = $this->factory()->store()->createStore();

        $product = $this->factory()->catalog()->getProductByName();

        $this->factory()->store()->deleteStore($store);

        $invoiceData = self::getStoreInvoiceData($store->id, null, $product->id);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $postResponse
        );

        Assert::assertJsonPathCount(0, 'errors.children.supplier.errors', $postResponse);
    }

    public function testPostWithDeletedSupplierAndStore()
    {
        $store = $this->factory()->store()->createStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProductByName();

        $this->factory()->clear();
        $this->factory()->supplier()->deleteSupplier($supplier);
        $this->factory()->store()->deleteStore($store);

        $invoiceData = self::getStoreInvoiceData($store->id, $supplier->id, $product->id);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $postResponse
        );
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $postResponse
        );
    }

    public function testPutWithDeletedSupplier()
    {
        $store = $this->factory()->store()->createStore();

        $product = $this->factory()->catalog()->getProductByName();

        $supplier = $this->factory()->supplier()->getSupplier();

        $invoiceData = self::getStoreInvoiceData($store->id, $supplier->id, $product->id);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $this->factory()->supplier()->deleteSupplier($supplier);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$postResponse['id']}",
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $putResponse
        );

        Assert::assertJsonPathCount(0, 'errors.children.store.errors', $putResponse);
    }

    public function testPutWithDeletedStore()
    {
        $store = $this->factory()->store()->createStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProductByName();

        $invoiceData = self::getStoreInvoiceData($store->id, $supplier->id, $product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $this->factory()
            ->receipt()
            ->createSale($store)
            ->createReceiptProduct($product->id, 10, 6.00)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$postResponse['id']}",
            $invoiceData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $putResponse
        );
        Assert::assertJsonPathCount(0, 'errors.children.supplier.errors.0', $putResponse);
    }

    public function testPutWithDeletedSupplierAndStore()
    {
        $store = $this->factory()->store()->createStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProductByName();

        $invoiceData = InvoiceBuilder::create($store->id, null, $supplier->id)
            ->addProduct($product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        $this->factory()
            ->receipt()
                ->createSale($store)
                ->createReceiptProduct($product->id, 10, 6.00)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store);
        $this->factory()->supplier()->deleteSupplier($supplier);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$postResponse['id']}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $putResponse
        );
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $putResponse
        );
    }

    public function testPutWithOriginalStoreDeleted()
    {
        $store1 = $this->factory()->store()->createStore('Store 1');
        $store2 = $this->factory()->store()->createStore('Store 2');
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProductByName();

        $invoiceData = InvoiceBuilder::create($store1->id, null, $supplier->id)
            ->addProduct($product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        $this->factory()
            ->receipt()
                ->createSale($store1)
                ->createReceiptProduct($product->id, 10, 6.00)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store1);

        $invoiceData->setStore($store2->id);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$postResponse['id']}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $putResponse
        );
        Assert::assertJsonPathCount(0, 'errors.children.supplier.errors.0', $putResponse);
    }

    public function testPutWithOriginalSupplierDeleted()
    {
        $store = $this->factory()->store()->createStore();
        $product = $this->factory()->catalog()->getProductByName();
        $supplier1 = $this->factory()->supplier()->getSupplier('supplier 1');
        $supplier2 = $this->factory()->supplier()->getSupplier('supplier 2');

        $invoiceData = InvoiceBuilder::create($store->id, null, $supplier1->id)
            ->addProduct($product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        $this->factory()->supplier()->deleteSupplier($supplier1);

        $invoiceData->setSupplier($supplier2->id);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/invoices/{$postResponse['id']}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $putResponse
        );

        Assert::assertJsonPathCount(0, 'errors.children.store.errors', $putResponse);
    }

    public function testPutInStoreWithOriginalSupplierDeleted()
    {
        $store = $this->factory()->store()->createStore();
        $product = $this->factory()->catalog()->getProductByName();
        $supplier1 = $this->factory()->supplier()->getSupplier('supplier 1');
        $supplier2 = $this->factory()->supplier()->getSupplier('supplier 2');

        $invoiceData = InvoiceBuilder::create(null, null, $supplier1->id)
            ->addProduct($product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(201);

        $this->factory()->supplier()->deleteSupplier($supplier1);

        $invoiceData->setSupplier($supplier2->id);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$postResponse['id']}",
            $invoiceData->toArray()
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного поставщика запрещены',
            'errors.children.supplier.errors.0',
            $putResponse
        );
    }

    public function testDeleteWithDeletedStore()
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProductByName();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 10, 5.12)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store)
                ->createReceiptProduct($product->id, 10, 7.49)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->client->setCatchException();
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/invoices/{$invoice->id}"
        );

        $this->assertResponseCode(409);
        Assert::assertJsonPathEquals(
            'Удаление операции с участием удаленного магазина запрещено',
            'message',
            $deleteResponse
        );
    }

    public function testDeleteWithDeletedSupplier()
    {
        $supplier = $this->factory()->supplier()->getSupplier();

        $product = $this->factory()->catalog()->getProductByName();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), null, $supplier->id)
                ->createInvoiceProduct($product->id)
            ->flush();

        $this->factory()->supplier()->deleteSupplier($supplier);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->client->setCatchException();
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/invoices/{$invoice->id}"
        );

        $this->assertResponseCode(409);
        Assert::assertJsonPathEquals(
            'Удаление операции с участием удаленного поставщика запрещено',
            'message',
            $deleteResponse
        );
    }

    public function testDeleteWithDeletedStoreAndSupplier()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->getProductByName();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 10, 5.12)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store)
                ->createReceiptProduct($product->id, 10, 7.49)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store);
        $this->factory()->supplier()->deleteSupplier($supplier);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->client->setCatchException();
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/invoices/{$invoice->id}"
        );

        $this->assertResponseCode(409);

        $expectedMessage = <<<EOF
Удаление операции с участием удаленного магазина запрещено
Удаление операции с участием удаленного поставщика запрещено
EOF;

        Assert::assertJsonPathEquals($expectedMessage, 'message', $deleteResponse);
    }

    /**
     * @param string $invoiceId
     */
    protected function assertInvoiceDelete($invoiceId)
    {
        $invoice = $this->getInvoiceRepository()->find($invoiceId);
        $this->assertNull($invoice);
    }

    /**
     * @param string $invoiceProductId
     */
    protected function assertInvoiceProductDelete($invoiceProductId)
    {
        $invoiceProduct = $this->getInvoiceProductRepository()->find($invoiceProductId);
        $this->assertNull($invoiceProduct);
    }

    /**
     * @return InvoiceRepository
     */
    protected function getInvoiceRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.invoice');
    }

    /**
     * @return StockMovementProductRepository
     */
    protected function getInvoiceProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.invoice_product');
    }

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->getContainer()->get('lighthouse.core.types.numeric.factory');
    }

    /**
     * @return StoreProductMetricsCalculator
     */
    protected function getMetricsCalculator()
    {
        return $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
    }
}
