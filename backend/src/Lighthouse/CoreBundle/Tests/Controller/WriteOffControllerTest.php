<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\WriteOffBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $date = strtotime('-1 day');

        $writeOffData = WriteOffBuilder::create(date('c', $date), $store->id)
            ->addProduct($productId)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs',
            $writeOffData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathCount(1, 'products.*.product', $postResponse);
        Assert::assertJsonPathEquals('10001', 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $postResponse);
    }

    /**
     * @dataProvider validationWriteOffProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffData = WriteOffBuilder::create('2012-07-11', $store->id)
            ->addProduct($productId)
            ->toArray($data);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs',
            $writeOffData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    /**
     * @dataProvider validationWriteOffProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPutWriteOffValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $postData = WriteOffBuilder::create('11.07.2012', $store->id)
            ->addProduct($productId)
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/writeOffs/{$writeOffId}",
            $putData
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
    public function validationWriteOffProvider()
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
        );
    }

    public function testGetAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store, '2012-05-23T15:12:05+0400')
                ->createWriteOffProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOff->id, 'id', $getResponse);
        Assert::assertJsonPathEquals('10001', 'number', $getResponse);
        Assert::assertJsonPathEquals('2012-05-23T15:12:05+0400', 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $productId = $this->createProduct();
        $this->factory()
            ->writeOff()
                ->createWriteOff()
                ->createWriteOffProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeOffs/invalidId'
        );

        $this->assertResponseCode(404);

        // There is not message in debug=false mode
        Assert::assertJsonPathContains('not found', 'message', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertNotJsonHasPath('number', $getResponse);
        Assert::assertNotJsonHasPath('date', $getResponse);
    }

    public function testWriteOffTotals()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        // Create writeoff with product#1
        $writeOffData = WriteOffBuilder::create(null, $store->id)
            ->addProduct($productId1, 12, 5.99);

        $postResponse = $this->postWriteOff($writeOffData->toArray());
        $writeOffId = $postResponse['id'];

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        // Add product#2
        $writeOffData->addProduct($productId2, 3, 6.49);

        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        // Add product#3
        $writeOffData->addProduct($productId3, 1, 11.12);

        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st write off product quantity and price

        $writeOffData->setProduct(0, $productId1, 10, 6.99, 'because');

        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // update 2nd write off product product id

        $writeOffData->setProduct(1, $productId3, 3, 6.49, 'because');

        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $writeOffData->removeProduct(2);

        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 2, 'sumTotal' => 89.37));
    }

    /**
     * @param string $storeId
     * @param string $writeOffId
     * @param array $assertions
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @throws \PHPUnit_Framework_Exception
     */
    protected function assertWriteOff($storeId, $writeOffId, array $assertions = array())
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $writeOffJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeId}/writeOffs/{$writeOffId}"
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($writeOffJson, $assertions);
    }

    public function testGetWriteOffsAction()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId1, 12, 5.99, 'Порча')
                ->createWriteOffProduct($productId2, 3, 6.49, 'Порча')
                ->createWriteOffProduct($productId3, 1, 11.12, 'Порча')
            ->flush();

        $writeOff2 = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId1, 1, 6.92, 'Порча')
                ->createWriteOffProduct($productId2, 2, 3.49, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/writeOffs"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($writeOff1->id, '*.id', $response, 1);
        Assert::assertJsonPathEquals($writeOff2->id, '*.id', $response, 1);
    }

    public function testDepartmentManagerCantGetWriteOffsFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store1)
                ->createWriteOffProduct($productId)
            ->flush();

        $writeOff2 = $this->factory()
            ->writeOff()
                ->createWriteOff($store2)
                ->createWriteOffProduct($productId)
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store1->id}/writeOffs/{$writeOff1->id}"
        );

        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store2->id}/writeOffs/{$writeOff2->id}"
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/writeOffs/{$writeOff1->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            "/api/1/stores/{$store2->id}/writeOffs/{$writeOff2->id}"
        );

        $this->assertResponseCode(200);
    }

    public function testGetWriteOffNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId = $this->createProduct();
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store1)
                ->createWriteOffProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store1->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store1->id}/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(200);
    }

    /**
     * @param string $query
     * @param int $count
     * @param array $assertions
     *
     * @dataProvider writeOffFilterProvider
     */
    public function testWriteOffsFilter($query, $count, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');
        $productId3 = $this->createProduct('333');

        $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId1, 10, 6.98, 'Бой')
            ->flush();

        $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId2, 5, 10.12, 'Бой')
            ->flush();

        $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId3, 7, 67.32, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/writeOffs",
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
    public function writeOffFilterProvider()
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
     * @dataProvider validationWriteOffProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct();

        $writeOffData = WriteOffBuilder::create(null, $store->id)
            ->addProduct($productId, 7.99, 2, 'Сгнил товар')
            ->toArray();

        $writeOffData['products'][0] = $data + $writeOffData['products'][0];


        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs',
            $writeOffData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @dataProvider validationWriteOffProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffProductValidationGroups($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct();

        $writeOffData = WriteOffBuilder::create(null, $store->id)
            ->addProduct($productId, 7.99, 2, 'Сгнил товар')
            ->toArray();

        $writeOffData['products'][0] = $data + $writeOffData['products'][0];


        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs?validate=true&validationGroups=products',
            $writeOffData
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
    public function validationWriteOffProductProvider()
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
             * 'cause'
             ***********************************************************************************************/
            'not valid empty cause' => array(
                400,
                array('cause' => ''),
                array(
                    'errors.children.products.children.0.children.cause.errors.0' => 'Заполните это поле'
                ),
            ),
            'not valid cause long 1001' => array(
                400,
                array('cause' => str_repeat('z', 1001)),
                array(
                    'errors.children.products.children.0.children.cause.errors.0' => 'Не более 1000 символов'
                ),
            ),
            'valid cause long 1000' => array(
                201,
                array('cause' => str_repeat("z", 1000)),
            ),
            'valid cause special symbols' => array(
                201,
                array('cause' => '!@#$%^&^&*QWERTY}{}":<></.,][;.,`~\=0=-\\'),
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
        $writeOffData = WriteOffBuilder::create(null, $store->id)
            ->addProduct($productId1, 5, 3.49, 'Порча');

        $postResponse = $this->postWriteOff($writeOffData->toArray());
        $writeOffId = $postResponse['id'];

        $this->assertStoreProductTotals($store->id, $productId1, 5, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // change 1st product write off quantity
        $writeOffData->setProduct(0, $productId1, 7, 4.49, 'Порча');
        $putResponse1 = $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertStoreProductTotals($store->id, $productId1, 3, 4.99);

        Assert::assertNotJsonPathEquals($postResponse['products'][0]['id'], 'products.0.id', $putResponse1);

        // add 2nd write off product
        $writeOffData->addProduct($productId2, 4, 20.99, 'Бой посуды');
        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertStoreProductTotals($store->id, $productId2, 16, 6.99);

        // change 2nd product id
        $writeOffData->setProduct(1, $productId1, 4, 20.99, 'Бой посуды');
        $putResponse3 = $this->putWriteOff($writeOffId, $writeOffData->toArray());

        Assert::assertJsonPathEquals($productId1, 'products.1.product.id', $putResponse3);

        $this->assertStoreProductTotals($store->id, $productId1, -1, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // remove 2nd write off product
        $writeOffData->removeProduct(1);
        $this->putWriteOff($writeOffId, $writeOffData->toArray());

        $this->assertStoreProductTotals($store->id, $productId1, 3, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);

        // remove write off
        $this->deleteWriteOff($writeOffId);

        $this->assertStoreProductTotals($store->id, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($store->id, $productId2, 20, 6.99);
    }

    public function testProductDataDoesNotChangeInWriteOffAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId, 10, 5.99, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $writeoffResponse1 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $writeoffResponse1, 1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $writeoffResponse2 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $writeoffResponse2, 1);

        $this->assertProduct($productId, array('name' => 'Кефир 5%'));
    }

    /**
     * @dataProvider departmentManagerCanNotAccessWriteOffFromAnotherStoreProvider
     * @param string $method
     * @param string $url
     * @param int $expectedCode
     * @param bool $sendData
     */
    public function testDepartmentManagerCanNotAccessWriteOffFromAnotherStore(
        $method,
        $url,
        $expectedCode,
        $sendData = false
    ) {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store1)
                ->createWriteOffProduct($productId, 2, 20, 'Бой')
            ->flush();
        $writeOff2 = $this->factory()
            ->writeOff()
                ->createWriteOff($store2)
                ->createWriteOffProduct($productId, 1, 10, 'Порча')
            ->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        if ($sendData) {
            $data = WriteOffBuilder::create()
                ->addProduct($productId)
                ->toArray();
        } else {
            $data = null;
        }

        $url1 = strtr(
            $url,
            array(
                '{store}' => $store1->id,
                '{writeOff}' => $writeOff1->id,
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
                '{writeOff}' => $writeOff2->id,
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
    public function departmentManagerCanNotAccessWriteOffFromAnotherStoreProvider()
    {
        return array(
            'GET' => array(
                'GET',
                '/api/1/stores/{store}/writeOffs/{writeOff}',
                200,
                false
            ),
            'POST' => array(
                'POST',
                '/api/1/stores/{store}/writeOffs',
                201,
                true
            ),
            'PUT' => array(
                'PUT',
                '/api/1/stores/{store}/writeOffs/{writeOff}',
                200,
                true
            ),
        );
    }

    public function testPutWithEmptyQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId, 1, 9.99, 'Порча')
            ->flush();

        $putData = WriteOffBuilder::create(null, $store->id)
            ->addProduct($productId, '', 9.99, 'Порча')
            ->toArray();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/writeOffs/{$writeOff->id}",
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

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId1, 2, 5.99, 'Порча')
                ->createWriteOffProduct($productId2, 1, 6.99, 'Порча')
                ->createWriteOffProduct($productId3, 3, 2.59, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $storeGetResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/writeOffs/{$writeOff->id}"
        );
        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('products.*.product.subCategory', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.writeOff', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category.group', $storeGetResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $storeGetResponse);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/writeOffs/{$writeOff->id}"
        );

        $this->assertSame($storeGetResponse, $getResponse);
    }

    public function testDeleteWriteOff()
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct(array('name' => 'Продукт'));

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/writeOffs/{$writeOff->id}"
        );

        $this->assertResponseCode(404);

        $this->assertWriteOffDelete($writeOff->id);
        $this->assertWriteOffProductDelete($writeOff->products[0]->id);
    }

    /**
     * @param string $invoiceId
     */
    protected function assertWriteOffDelete($invoiceId)
    {
        $invoice = $this->getWriteOffRepository()->find($invoiceId);
        $this->assertNull($invoice);

        $filterCollection = $this->getWriteOffRepository()->getDocumentManager()->getFilterCollection();
        $filterCollection->disable('softdeleteable');

        $invoice = $this->getWriteOffRepository()->find($invoiceId);
        $this->assertNull($invoice);

        $filterCollection->enable('softdeleteable');
    }

    /**
     * @param string $invoiceProductId
     */
    protected function assertWriteOffProductDelete($invoiceProductId)
    {
        $invoiceProduct = $this->getWriteOffProductRepository()->find($invoiceProductId);
        $this->assertNull($invoiceProduct);

        $filterCollection = $this->getWriteOffProductRepository()->getDocumentManager()->getFilterCollection();

        $filterCollection->disable('softdeleteable');

        $invoiceProduct = $this->getWriteOffProductRepository()->find($invoiceProductId);
        $this->assertNull($invoiceProduct);

        $filterCollection->enable('softdeleteable');
    }

    /**
     * @return WriteOffRepository
     */
    protected function getWriteOffRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.writeoff');
    }

    /**
     * @return WriteOffProductRepository
     */
    protected function getWriteOffProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.writeoff_product');
    }

    /**
     * @param array $data
     * @return array
     */
    protected function postWriteOff(array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeOffs',
            $data
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse;
    }

    /**
     * @param string $writeOffId
     * @param array $data
     * @return array
     */
    protected function putWriteOff($writeOffId, array $data)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/writeOffs/{$writeOffId}",
            $data
        );

        $this->assertResponseCode(200);

        return $putResponse;
    }

    /**
     * @param string $writeOffId
     */
    protected function deleteWriteOff($writeOffId)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/writeOffs/{$writeOffId}"
        );

        $this->assertResponseCode(204);
        $this->assertNull($deleteResponse);
    }
}
