<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInRepository;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\StockMovementBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StockInControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $date = strtotime('-1 day');

        $stockInData = StockMovementBuilder::create(date('c', $date), $store->id)
            ->addProduct($product->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData->toArray()
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathCount(1, 'products.*.product', $postResponse);
        Assert::assertJsonPathEquals('10001', 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $postResponse);
    }

    /**
     * @dataProvider validationStockInProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostStockInValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $stockInData = StockMovementBuilder::create('2012-07-11', $store->id)
            ->addProduct($product->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData->toArray($data)
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    /**
     * @dataProvider validationStockInProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPutStockInValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();
        $postData = StockMovementBuilder::create('11.07.2012', $store->id)
            ->addProduct($product->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $postData->toArray()
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        $stockInId = $postResponse['id'];

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stockIns/{$stockInId}",
            $postData->toArray($data)
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $putResponse);
        }
    }

    /**
     * @return array
     */
    public function validationStockInProvider()
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
        );
    }

    public function testGetAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store, '2012-05-23T15:12:05+0400')
                ->createStockInProduct($product->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($stockIn->id, 'id', $getResponse);
        Assert::assertJsonPathEquals('10001', 'number', $getResponse);
        Assert::assertJsonPathEquals('2012-05-23T15:12:05+0400', 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $product = $this->factory()->catalog()->getProduct();
        $this->factory()
            ->stockIn()
                ->createStockIn()
                ->createStockInProduct($product->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stockIns/invalidId'
        );

        $this->assertResponseCode(404);

        // There is not message in debug=false mode
        Assert::assertJsonPathContains('not found', 'message', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertNotJsonHasPath('number', $getResponse);
        Assert::assertNotJsonHasPath('date', $getResponse);
    }

    public function testStockInTotals()
    {
        $store = $this->factory()->store()->getStore();
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        // Create stockin with product#1
        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($products['1']->id, 12, 5.99);

        $postResponse = $this->postStockIn($stockInData->toArray());
        $stockInId = $postResponse['id'];

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        // Add product#2
        $stockInData->addProduct($products['2']->id, 3, 6.49);

        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        // Add product#3
        $stockInData->addProduct($products['3']->id, 1, 11.12);

        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st stock in product quantity and price

        $stockInData->setProduct(0, $products['1']->id, 10, 6.99);

        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // update 2nd stock in product product id

        $stockInData->setProduct(1, $products['3']->id, 3, 6.49);

        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd stock in product

        $stockInData->removeProduct(2);

        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStockIn($store->id, $stockInId, array('itemsCount' => 2, 'sumTotal' => 89.37));
    }

    /**
     * @param string $storeId
     * @param string $stockInId
     * @param array $assertions
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @throws \PHPUnit_Framework_Exception
     */
    protected function assertStockIn($storeId, $stockInId, array $assertions = array())
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $stockInJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeId}/stockIns/{$stockInId}"
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($stockInJson, $assertions);
    }

    public function testGetStockInsAction()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $stockIn1 = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['1']->id, 12, 5.99, 'Порча')
                ->createStockInProduct($products['2']->id, 3, 6.49, 'Порча')
                ->createStockInProduct($products['3']->id, 1, 11.12, 'Порча')
            ->flush();

        $stockIn2 = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['1']->id, 1, 6.92, 'Порча')
                ->createStockInProduct($products['2']->id, 2, 3.49, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/stockIns"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($stockIn1->id, '*.id', $response, 1);
        Assert::assertJsonPathEquals($stockIn2->id, '*.id', $response, 1);
    }

    public function testDepartmentManagerCantGetStockInsFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $product = $this->factory()->catalog()->getProduct();

        $stockIn1 = $this->factory()
            ->stockIn()
                ->createStockIn($store1)
                ->createStockInProduct($product->id)
            ->flush();

        $stockIn2 = $this->factory()
            ->stockIn()
                ->createStockIn($store2)
                ->createStockInProduct($product->id)
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store1->id}/stockIns/{$stockIn1->id}"
        );

        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store2->id}/stockIns/{$stockIn2->id}"
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/stockIns/{$stockIn1->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store2->id}/stockIns/{$stockIn2->id}"
        );

        $this->assertResponseCode(200);
    }

    public function testGetStockInNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $product = $this->factory()->catalog()->getProduct();
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store1)
                ->createStockInProduct($product->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store1->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store1->id}/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(200);
    }

    /**
     * @param string $query
     * @param int $count
     * @param array $assertions
     *
     * @dataProvider stockInFilterProvider
     */
    public function testStockInsFilter($query, $count, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('111', '222', '333'));

        $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['111']->id, 10, 6.98, 'Бой')
            ->flush();

        $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['222']->id, 5, 10.12, 'Бой')
            ->flush();

        $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['333']->id, 7, 67.32, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/stockIns",
            null,
            array('number' => $query)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount($count, '*.id', $response);
        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function stockInFilterProvider()
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
            'none found: not existing number' => array(
                '1234',
                0,
            ),
            'none found: empty number' => array(
                '',
                0,
            ),
        );
    }

    /**
     * @dataProvider validationStockInProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostStockInProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProduct();

        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product->id, 7.99, 2)
            ->mergeProduct(0, $data);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData->toArray()
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @dataProvider validationStockInProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostStockInProductValidationGroups($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProduct();

        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product->id, 7.99, 2)
            ->mergeProduct(0, $data);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns?validate=true&validationGroups=products',
            $stockInData->toArray()
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
    public function validationStockInProductProvider()
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

    public function testProductAmountChangeOnStockIn()
    {
        $store = $this->factory()->store()->getStore();

        $product1 = $this->factory()->catalog()->getProduct(1);
        $product2 = $this->factory()->catalog()->getProduct(2);

        $this->assertStoreProductTotals($store->id, $product1->id, 0);

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product1->id, 10, 4.99)
                ->createInvoiceProduct($product2->id, 20, 6.99)
            ->flush();

        $this->assertStoreProductTotals($store->id, $product1->id, 10, 4.99);
        $this->assertStoreProductTotals($store->id, $product2->id, 20, 6.99);

        // create product 1 stock in
        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product1->id, 5, 3.49);

        $postResponse = $this->postStockIn($stockInData->toArray());
        $stockInId = $postResponse['id'];

        $this->assertStoreProductTotals($store->id, $product1->id, 15, 4.99);
        $this->assertStoreProductTotals($store->id, $product2->id, 20, 6.99);

        // change 1st product stock in quantity
        $stockInData->setProduct(0, $product1->id, 7, 4.49);
        $putResponse1 = $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStoreProductTotals($store->id, $product1->id, 17, 4.99);

        Assert::assertNotJsonPathEquals($postResponse['products'][0]['id'], 'products.0.id', $putResponse1);

        // add 2nd stock in product
        $stockInData->addProduct($product2->id, 4, 20.99);
        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStoreProductTotals($store->id, $product2->id, 24, 6.99);

        // change 2nd product id
        $stockInData->setProduct(1, $product1->id, 4, 20.99);
        $putResponse3 = $this->putStockIn($stockInId, $stockInData->toArray());

        Assert::assertJsonPathEquals($product1->id, 'products.1.product.id', $putResponse3);

        $this->assertStoreProductTotals($store->id, $product1->id, 21, 4.99);
        $this->assertStoreProductTotals($store->id, $product2->id, 20, 6.99);

        // remove 2nd stock in product
        $stockInData->removeProduct(1);
        $this->putStockIn($stockInId, $stockInData->toArray());

        $this->assertStoreProductTotals($store->id, $product1->id, 17, 4.99);
        $this->assertStoreProductTotals($store->id, $product2->id, 20, 6.99);

        // remove stock in
        $this->deleteStockIn($stockInId);

        $this->assertStoreProductTotals($store->id, $product1->id, 10, 4.99);
        $this->assertStoreProductTotals($store->id, $product2->id, 20, 6.99);
    }

    public function testProductDataDoesNotChangeInStockInAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct('Кефир 1%');
        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($product->id, 10, 5.99, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $stockinResponse1 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $stockinResponse1, 1);

        $this->updateProduct($product->id, array('name' => 'Кефир 5%'));

        $stockinResponse2 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $stockinResponse2, 1);

        $this->assertProduct($product->id, array('name' => 'Кефир 5%'));
    }

    /**
     * @dataProvider departmentManagerCanNotAccessStockInFromAnotherStoreProvider
     * @param string $method
     * @param string $url
     * @param int $expectedCode
     * @param bool $sendData
     */
    public function testDepartmentManagerCanNotAccessStockInFromAnotherStore(
        $method,
        $url,
        $expectedCode,
        $sendData = false
    ) {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $product = $this->factory()->catalog()->getProduct();

        $stockIn1 = $this->factory()
            ->stockIn()
                ->createStockIn($store1)
                ->createStockInProduct($product->id, 2, 20, 'Бой')
            ->flush();
        $stockIn2 = $this->factory()
            ->stockIn()
                ->createStockIn($store2)
                ->createStockInProduct($product->id, 1, 10, 'Порча')
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        if ($sendData) {
            $data = StockMovementBuilder::create()
                ->addProduct($product->id)
                ->toArray();
        } else {
            $data = null;
        }

        $url1 = strtr(
            $url,
            array(
                '{store}' => $store1->id,
                '{stockIn}' => $stockIn1->id,
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
                '{stockIn}' => $stockIn2->id,
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
    public function departmentManagerCanNotAccessStockInFromAnotherStoreProvider()
    {
        return array(
            'GET' => array(
                'GET',
                '/api/1/stores/{store}/stockIns/{stockIn}',
                200,
                false
            ),
            'POST' => array(
                'POST',
                '/api/1/stores/{store}/stockIns',
                201,
                true
            ),
            'PUT' => array(
                'PUT',
                '/api/1/stores/{store}/stockIns/{stockIn}',
                200,
                true
            ),
        );
    }

    public function testPutWithEmptyQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($product->id, 1, 9.99, 'Порча')
            ->flush();

        $putData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product->id, '', 9.99);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stockIns/{$stockIn->id}",
            $putData->toArray()
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
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($products['1']->id, 2, 5.99, 'Порча')
                ->createStockInProduct($products['2']->id, 1, 6.99, 'Порча')
                ->createStockInProduct($products['3']->id, 3, 2.59, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $storeGetResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/stockIns/{$stockIn->id}"
        );
        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('products.*.product.subCategory', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.stockIn', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category.group', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $storeGetResponse);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stockIns/{$stockIn->id}"
        );

        $this->assertSame($storeGetResponse, $getResponse);
    }

    public function testDeleteStockIn()
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProduct('Продукт');

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($product->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(404);

        $this->assertStockInDelete($stockIn->id);
        $this->assertStockInProductDelete($stockIn->products[0]->id);
    }

    public function testPostWithDeletedStore()
    {
        $store = $this->factory()->store()->createStore();

        $product = $this->factory()->catalog()->getProductByName();

        $this->factory()->store()->deleteStore($store);

        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product->id, 10, 5.99)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $postResponse
        );
    }

    public function testPutWithDeletedStore()
    {
        $store = $this->factory()->store()->createStore();
        $product = $this->factory()->catalog()->getProductByName();

        $stockInData = StockMovementBuilder::create(null, $store->id)
            ->addProduct($product->id, 10, 5.99)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData
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
            "/api/1/stockIns/{$postResponse['id']}",
            $stockInData
        );

        $this->assertResponseCode(400);

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

        $product = $this->factory()->catalog()->getProductByName();

        $stockInData = StockMovementBuilder::create(null, $store1->id)
            ->addProduct($product->id, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $stockInData->toArray()
        );

        $this->assertResponseCode(201);

        $this->factory()
            ->receipt()
                ->createSale($store1)
                ->createReceiptProduct($product->id, 10, 6.00)
            ->flush();

        $this->factory()->clear();
        $this->factory()->store()->deleteStore($store1);

        $stockInData->setStore($store2->id);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stockIns/{$postResponse['id']}",
            $stockInData->toArray()
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.children.store.errors.0',
            $putResponse
        );
    }

    public function testDeleteWithDeletedStore()
    {
        $store = $this->factory()->store()->getStore();

        $product = $this->factory()->catalog()->getProductByName();

        $stockIn = $this->factory()
            ->stockIn()
                ->createStockIn($store)
                ->createStockInProduct($product->id, 10, 5.12)
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
            "/api/1/stockIns/{$stockIn->id}"
        );

        $this->assertResponseCode(409);
        Assert::assertJsonPathEquals(
            'Удаление операции с участием удаленного магазина запрещено',
            'message',
            $deleteResponse
        );
    }

    /**
     * @param string $invoiceId
     */
    protected function assertStockInDelete($invoiceId)
    {
        $invoice = $this->getStockInRepository()->find($invoiceId);
        $this->assertNull($invoice);
    }

    /**
     * @param string $invoiceProductId
     */
    protected function assertStockInProductDelete($invoiceProductId)
    {
        $invoiceProduct = $this->getStockInProductRepository()->find($invoiceProductId);
        $this->assertNull($invoiceProduct);
    }

    /**
     * @return StockInRepository
     */
    protected function getStockInRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.stockin');
    }

    /**
     * @return StockMovementProductRepository
     */
    protected function getStockInProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.stockin_product');
    }

    /**
     * @param array $data
     * @return array
     */
    protected function postStockIn(array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stockIns',
            $data
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse;
    }

    /**
     * @param string $stockInId
     * @param array $data
     * @return array
     */
    protected function putStockIn($stockInId, array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stockIns/{$stockInId}",
            $data
        );

        $this->assertResponseCode(200);

        return $putResponse;
    }

    /**
     * @param string $stockInId
     */
    protected function deleteStockIn($stockInId)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/stockIns/{$stockInId}"
        );

        $this->assertResponseCode(204);
        $this->assertNull($deleteResponse);
    }
}
