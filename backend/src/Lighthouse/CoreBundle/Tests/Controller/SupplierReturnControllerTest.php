<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\SupplierReturnBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;

class SupplierReturnControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();
        $date = strtotime('-1 day');

        $supplierReturnData = SupplierReturnBuilder::create(date('c', $date), $store->id, $supplier->id)
            ->addProduct($productId)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns',
            $supplierReturnData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathCount(1, 'products.*.product', $postResponse);
        Assert::assertJsonPathEquals('10001', 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $postResponse);
    }

    /**
     * @dataProvider validationSupplierReturnProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostSupplierReturnValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();
        $supplierReturnData = SupplierReturnBuilder::create('2012-07-11', $store->id, $supplier->id)
            ->addProduct($productId)
            ->toArray($data);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns',
            $supplierReturnData
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
     * @dataProvider validationSupplierReturnProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPutSupplierReturnValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();
        $postData = SupplierReturnBuilder::create('11.07.2012', $store->id, $supplier->id)
            ->addProduct($productId)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        $supplierReturnId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/supplierReturns/{$supplierReturnId}",
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            if (is_bool($expected)) {
                Assert::assertJsonPathEquals($expected, $path, $putResponse);
            } else {
                Assert::assertJsonPathContains($expected, $path, $putResponse);
            }
        }
    }

    /**
     * @return array
     */
    public function validationSupplierReturnProvider()
    {
        return array(
            'not valid empty date' => array(
                400,
                array('date' => ''),
                array(
                    'errors.children.date.errors.0' => 'Заполните это поле'
                )
            ),
            'valid date' => array(
                201,
                array('date' => '2013-12-31')
            ),
            'not valid date' => array(
                400,
                array('date' => '2013-2sd-31'),
                array(
                    'errors.children.date.errors.0'
                    =>
                    'Вы ввели неверную дату 2013-2sd-31, формат должен быть следующий дд.мм.гггг'
                )
            ),
            'not valid number given' => array(
                400,
                array('number' => '1111'),
                array(
                    'errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "number"'
                )
            ),
            'not valid empty products' => array(
                400,
                array('products' => array()),
                array(
                    'errors.errors.0' => 'Нужно добавить минимум один товар'
                )
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
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

    public function testGetAction()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store, '2012-05-23T15:12:05+0400', $supplier)
                ->createSupplierReturnProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($supplierReturn->id, 'id', $getResponse);
        Assert::assertJsonPathEquals('10001', 'number', $getResponse);
        Assert::assertJsonPathEquals('2012-05-23T15:12:05+0400', 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $productId = $this->createProduct();
        $this->factory()
            ->supplierReturn()
                ->createSupplierReturn()
                ->createSupplierReturnProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/supplierReturns/invalidId'
        );

        $this->assertResponseCode(404);

        // There is not message in debug=false mode
        Assert::assertJsonPathContains('not found', 'message', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertNotJsonHasPath('number', $getResponse);
        Assert::assertNotJsonHasPath('date', $getResponse);
    }

    public function testSupplierReturnTotals()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        // Create supplier return with product#1
        $supplierReturnData = SupplierReturnBuilder::create(null, $store->id)
            ->addProduct($productId1, 12, 5.99);

        $postResponse = $this->postSupplierReturn($supplierReturnData->toArray());
        $supplierReturnId = $postResponse['id'];

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        // Add product#2
        $supplierReturnData->addProduct($productId2, 3, 6.49);

        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        // Add product#3
        $supplierReturnData->addProduct($productId3, 1, 11.12);

        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st write off product quantity and price

        $supplierReturnData->setProduct(0, $productId1, 10, 6.99);

        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // update 2nd write off product product id

        $supplierReturnData->setProduct(1, $productId3, 3, 6.49);

        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $supplierReturnData->removeProduct(2);

        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertSupplierReturn($store->id, $supplierReturnId, array('itemsCount' => 2, 'sumTotal' => 89.37));
    }

    /**
     * @param string $storeId
     * @param string $supplierReturnId
     * @param array $assertions
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @throws \PHPUnit_Framework_Exception
     */
    protected function assertSupplierReturn($storeId, $supplierReturnId, array $assertions = array())
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $supplierReturnJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeId}/supplierReturns/{$supplierReturnId}"
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($supplierReturnJson, $assertions);
    }

    public function testDepartmentManagerCantGetSupplierReturnsFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $supplierReturn1 = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store1)
                ->createSupplierReturnProduct($productId)
            ->flush();

        $supplierReturn2 = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store2)
                ->createSupplierReturnProduct($productId)
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store1->id}/supplierReturns/{$supplierReturn1->id}"
        );

        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store2->id}/supplierReturns/{$supplierReturn2->id}"
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/supplierReturns/{$supplierReturn1->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store2->id}/supplierReturns/{$supplierReturn2->id}"
        );

        $this->assertResponseCode(200);
    }

    public function testGetSupplierReturnNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId = $this->createProduct();
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store1)
                ->createSupplierReturnProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store1->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store1->id}/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider validationSupplierReturnProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostSupplierReturnProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct();

        $supplierReturnData = SupplierReturnBuilder::create(null, $store->id)
            ->addProduct($productId, 7.99, 2)
            ->toArray();

        $supplierReturnData['products'][0] = $data + $supplierReturnData['products'][0];


        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns',
            $supplierReturnData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @dataProvider validationSupplierReturnProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostSupplierReturnProductValidationGroups(
        $expectedCode,
        array $data,
        array $assertions = array()
    ) {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct();

        $supplierReturnData = SupplierReturnBuilder::create(null, $store->id)
            ->addProduct($productId, 7.99, 2)
            ->toArray();

        $supplierReturnData['products'][0] = $data + $supplierReturnData['products'][0];


        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns?validate=true&validationGroups=products',
            $supplierReturnData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);

        if (400 != $expectedCode) {
            Assert::assertNotJsonHasPath('id', $postResponse);
        }
    }

    /**
     * @return array
     */
    public function validationSupplierReturnProductProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
                array('quantity' => 7),
                array('products.0.quantity' => 7)
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0' => 'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0' => 'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0' => 'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0' => 'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                201,
                array('quantity' => 2.5),
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
            'float quantity with coma' => array(
                201,
                array('quantity' => '2,5'),
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
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
                array('price' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('price' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('price' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('price' => ''),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('price' => '10,898'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('price' => '10.898'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('price' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('price' => 'not a number'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('price' => 0),
            ),
            'not valid price negative' => array(
                400,
                array('price' => -10),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('price' => 2000000001),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('price' => '100000000'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('price' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('price' => '10000001'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
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
                    'errors.children.products.children.0.children.product.errors.0' => 'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'errors.children.products.children.0.children.product.errors.0' => 'Заполните это поле'
                ),
            ),
            /***********************************************************************************************
             * 'totals'
             ***********************************************************************************************/
            'valid invoice totals recalc' => array(
                201,
                array('quantity' => 9, 'price' => 5.99),
                array('sumTotal' => 53.91, 'itemsCount' => 1)
            ),
        );
    }

    public function testProductAmountChangeOnWriteOf()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(1);
        $productId2 = $this->createProduct(2);

        $this->assertStoreProductTotals($store->id, $productId1, 0);

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId1, 10, 4.99)
                ->createInvoiceProduct($productId2, 20, 6.99)
            ->flush();

        $this->assertStoreProductTotals($store->id, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // create product 1 write off
        $supplierReturnData = SupplierReturnBuilder::create(null, $store->id)
            ->addProduct($productId1, 5, 3.49);

        $postResponse = $this->postSupplierReturn($supplierReturnData->toArray());
        $supplierReturnId = $postResponse['id'];

        $this->assertStoreProductTotals($store->id, $productId1, 5, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // change 1st product write off quantity
        $supplierReturnData->setProduct(0, $productId1, 7, 4.49);
        $putResponse1 = $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertStoreProductTotals($store->id, $productId1, 3, 4.99);

        Assert::assertNotJsonPathEquals($postResponse['products'][0]['id'], 'products.0.id', $putResponse1);

        // add 2nd write off product
        $supplierReturnData->addProduct($productId2, 4, 20.99);
        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertStoreProductTotals($store->id, $productId2, 16, 6.99);

        // change 2nd product id
        $supplierReturnData->setProduct(1, $productId1, 4, 20.99);
        $putResponse3 = $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        Assert::assertJsonPathEquals($productId1, 'products.1.product.id', $putResponse3);

        $this->assertStoreProductTotals($store->id, $productId1, -1, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // remove 2nd write off product
        $supplierReturnData->removeProduct(1);
        $this->putSupplierReturn($supplierReturnId, $supplierReturnData->toArray());

        $this->assertStoreProductTotals($store->id, $productId1, 3, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // remove write off
        $this->deleteSupplierReturn($supplierReturnId);

        $this->assertStoreProductTotals($store->id, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);
    }

    public function testProductDataDoesNotChangeInSupplierReturnAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store)
                ->createSupplierReturnProduct($productId, 10, 5.99, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $supplierReturnResponse1 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $supplierReturnResponse1, 1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $supplierReturnResponse2 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $supplierReturnResponse2, 1);

        $this->assertProduct($productId, array('name' => 'Кефир 5%'));
    }

    /**
     * @dataProvider departmentManagerCanNotAccessSupplierReturnFromAnotherStoreProvider
     * @param string $method
     * @param string $url
     * @param int $expectedCode
     * @param bool $sendData
     */
    public function testDepartmentManagerCanNotAccessSupplierReturnFromAnotherStore(
        $method,
        $url,
        $expectedCode,
        $sendData = false
    ) {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $supplierReturn1 = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store1)
                ->createSupplierReturnProduct($productId, 2, 20, 'Бой')
            ->flush();
        $supplierReturn2 = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store2)
                ->createSupplierReturnProduct($productId, 1, 10, 'Порча')
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        if ($sendData) {
            $data = SupplierReturnBuilder::create()
                ->addProduct($productId)
                ->toArray();
        } else {
            $data = null;
        }

        $url1 = strtr(
            $url,
            array(
                '{store}' => $store1->id,
                '{supplierReturn}' => $supplierReturn1->id,
            )
        );

        $this->client->setCatchException();
        $this->clientJsonRequest($accessToken2, $method, $url1, $data);
        $this->assertResponseCode(403);

        $this->clientJsonRequest($accessToken1, $method, $url1, $data);
        $this->assertResponseCode($expectedCode);

        $url2 = strtr(
            $url,
            array(
                '{store}' => $store2->id,
                '{supplierReturn}' => $supplierReturn2->id,
            )
        );

        $this->client->setCatchException();
        $this->clientJsonRequest($accessToken1, $method, $url2, $data);
        $this->assertResponseCode(403);

        $this->clientJsonRequest($accessToken2, $method, $url2, $data);
        $this->assertResponseCode($expectedCode);
    }

    /**
     * @return array
     */
    public function departmentManagerCanNotAccessSupplierReturnFromAnotherStoreProvider()
    {
        return array(
            'GET' => array(
                'GET',
                '/api/1/stores/{store}/supplierReturns/{supplierReturn}',
                200,
                false
            ),
            'POST' => array(
                'POST',
                '/api/1/stores/{store}/supplierReturns',
                201,
                true
            ),
            'PUT' => array(
                'PUT',
                '/api/1/stores/{store}/supplierReturns/{supplierReturn}',
                200,
                true
            ),
        );
    }

    public function testPutWithEmptyQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store)
                ->createSupplierReturnProduct($productId, 1, 9.99, 'Порча')
            ->flush();

        $putData = SupplierReturnBuilder::create(null, $store->id)
            ->addProduct($productId, '', 9.99)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/supplierReturns/{$supplierReturn->id}",
            $putData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function testProductCategoryIsNotExposed()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store)
                ->createSupplierReturnProduct($productId1, 2, 5.99, 'Порча')
                ->createSupplierReturnProduct($productId2, 1, 6.99, 'Порча')
                ->createSupplierReturnProduct($productId3, 3, 2.59, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $storeGetResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/supplierReturns/{$supplierReturn->id}"
        );
        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('products.*.product.subCategory', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.supplierReturn', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category.group', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $storeGetResponse);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertSame($storeGetResponse, $getResponse);
    }

    public function testDeleteSupplierReturn()
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct(array('name' => 'Продукт'));

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn($store)
                ->createSupplierReturnProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/supplierReturns/{$supplierReturn->id}"
        );

        $this->assertResponseCode(404);

        $this->assertSupplierReturnDelete($supplierReturn->id);
        $this->assertSupplierReturnProductDelete($supplierReturn->products[0]->id);
    }

    /**
     * @param string $invoiceId
     */
    protected function assertSupplierReturnDelete($invoiceId)
    {
        $invoice = $this->getSupplierReturnRepository()->find($invoiceId);
        $this->assertNull($invoice);
    }

    /**
     * @param string $invoiceProductId
     */
    protected function assertSupplierReturnProductDelete($invoiceProductId)
    {
        $invoiceProduct = $this->getSupplierReturnProductRepository()->find($invoiceProductId);
        $this->assertNull($invoiceProduct);
    }

    /**
     * @return SupplierReturnRepository
     */
    protected function getSupplierReturnRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.supplier_return');
    }

    /**
     * @return StockMovementProductRepository
     */
    protected function getSupplierReturnProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.supplier_return_product');
    }

    /**
     * @param array $data
     * @return array
     */
    protected function postSupplierReturn(array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/supplierReturns',
            $data
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse;
    }

    /**
     * @param string $supplierReturnId
     * @param array $data
     * @return array
     */
    protected function putSupplierReturn($supplierReturnId, array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/supplierReturns/{$supplierReturnId}",
            $data
        );

        $this->assertResponseCode(200);

        return $putResponse;
    }

    /**
     * @param string $supplierReturnId
     */
    protected function deleteSupplierReturn($supplierReturnId)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/supplierReturns/{$supplierReturnId}"
        );

        $this->assertResponseCode(204);
        $this->assertNull($deleteResponse);
    }
}
