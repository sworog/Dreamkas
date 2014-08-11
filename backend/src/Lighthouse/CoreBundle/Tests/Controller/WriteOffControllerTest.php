<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Client\Request\WriteOffBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Versionable\VersionRepository;

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
            '/api/1/writeoffs',
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
            '/api/1/writeoffs',
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
        $postData = array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/writeoffs',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId,
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
            '/api/1/writeoffs/' . $writeOff->id
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
            '/api/1/writeoffs/invalidId'
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

        $writeOffId = $this->createWriteOff('431', null, $store->id);

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => null, 'sumTotal' => null));

        $writeOffProductId1 = $this->createWriteOffProduct(
            $writeOffId,
            $productId1,
            12,
            5.99,
            'Порча',
            $store->id
        );

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        $writeOffProductId2 = $this->createWriteOffProduct(
            $writeOffId,
            $productId2,
            3,
            6.49,
            'Порча',
            $store->id
        );

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        $writeOffProductId3 = $this->createWriteOffProduct(
            $writeOffId,
            $productId3,
            1,
            11.12,
            'Порча',
            $store->id
        );

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st write off product quantity and price

        $putData = array(
            'product' => $productId1,
            'price' => 6.99,
            'quantity' => 10,
            'cause' => 'because',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            $putData
        );

        $this->assertResponseCode(200);

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // update 2nd write off product product id

        $putData = array(
            'product' => $productId3,
            'price' => 6.49,
            'quantity' => 3,
            'cause' => 'because',
        );

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            $putData
        );

        $this->assertResponseCode(200);

        $this->assertWriteOff($store->id, $writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId3
        );

        $this->assertResponseCode(204);

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
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId
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

        $writeOffId = $this->createWriteOff('4312', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId1, 12, 5.99, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId, $productId2, 3, 6.49, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId, $productId3, 1, 11.12, 'Порча', $store->id);

        $writeOffId2 = $this->createWriteOff('2', null, $store->id);
        $this->createWriteOffProduct($writeOffId2, $productId1, 1, 6.92, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId2, $productId2, 2, 3.49, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($writeOffId, '*.id', $response, 1);
        Assert::assertJsonPathEquals($writeOffId2, '*.id', $response, 1);
    }

    public function testDepartmentManagerCantGetWriteOffsFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $writeOffId1 = $this->createWriteOff('4313', null, $store1->id);
        $writeOffId2 = $this->createWriteOff('4314', null, $store2->id);

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId1
        );

        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store2->id . '/writeoffs/' . $writeOffId2
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId1
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store2->id . '/writeoffs/' . $writeOffId2
        );

        $this->assertResponseCode(200);
    }

    public function testGetWriteOffNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $writeOffId = $this->createWriteOff('444', null, $store1->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store1->id);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/writeoffs/' . $writeOffId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId
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

        $writeOffId1 = $this->createWriteOff('1234-89', null, $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId1, 10, 6.98, 'Бой', $store->id);

        $writeOffId2 = $this->createWriteOff('866-89', null, $store->id);
        $this->createWriteOffProduct($writeOffId2, $productId2, 5, 10.12, 'Бой', $store->id);

        $writeOffId3 = $this->createWriteOff('7561-89', null, $store->id);
        $this->createWriteOffProduct($writeOffId3, $productId3, 7, 67.32, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs',
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
                '866-89',
                1,
                array(
                    '0.number' => '866-89',
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

    public function testWriteOffsFilterOrder()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');

        $writeOffId1 = $this->createWriteOff('1234-89', '2013-03-17T16:12:33+0400', $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId1, 10, 6.98, 'Бой', $store->id);

        $writeOffId2 = $this->createWriteOff('1234-89', '2013-03-16T14:54:23+0400', $store->id);
        $this->createWriteOffProduct($writeOffId2, $productId2, 5, 10.12, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs',
            null,
            array('number' => '1234-89')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('1234-89', '0.number', $response);
        Assert::assertJsonPathEquals('2013-03-17T16:12:33+0400', '0.date', $response);
        Assert::assertJsonPathEquals('1234-89', '1.number', $response);
        Assert::assertJsonPathEquals('2013-03-16T14:54:23+0400', '1.date', $response);

        $writeOffId3 = $this->createWriteOff('1234-89', '2013-03-15T16:12:33+0400', $store->id);
        $this->createWriteOffProduct($writeOffId3, $productId1, 10, 6.98, 'Бой', $store->id);

        $writeOffId4 = $this->createWriteOff('867-89', '2013-03-16T14:54:23+0400', $store->id);
        $this->createWriteOffProduct($writeOffId4, $productId2, 5, 10.12, 'Бой', $store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs',
            null,
            array('number' => '1234-89')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(3, '*.id', $response);
        Assert::assertJsonPathEquals('1234-89', '0.number', $response);
        Assert::assertJsonPathEquals('2013-03-17T16:12:33+0400', '0.date', $response);
        Assert::assertJsonPathEquals('1234-89', '1.number', $response);
        Assert::assertJsonPathEquals('2013-03-16T14:54:23+0400', '1.date', $response);
        Assert::assertJsonPathEquals('1234-89', '2.number', $response);
        Assert::assertJsonPathEquals('2013-03-15T16:12:33+0400', '2.date', $response);
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
        $writeOffId = $this->createWriteOff('345-783', null, $store->id);
        $productId = $this->createProduct();

        $writeOffProductData = $data + array(
                'product' => $productId,
                'price' => 7.99,
                'quantity' => 2,
                'cause' => 'Сгнил товар'
            );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products',
            'POST',
            $writeOffProductData
        );
        $postResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions, true);
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
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'errors.children.quantity.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'errors.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'errors.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'errors.children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
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
                    'errors.children.quantity.errors.0'
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
                    'errors.children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                    'errors.children.quantity.errors.1'
                    =>
                        null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('quantity' => 'abc'),
                array(
                    'errors.children.quantity.errors.0'
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
                    'errors.children.price.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('price' => '10,898'),
                array(
                    'errors.children.price.errors.0'
                    =>
                        'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('price' => '10.898'),
                array(
                    'errors.children.price.errors.0'
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
                    'errors.children.price.errors.0'
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
                    'errors.children.price.errors.0'
                    =>
                        'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('price' => 2000000001),
                array(
                    'errors.children.price.errors.0'
                    =>
                        'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('price' => '100000000'),
                array(
                    'errors.children.price.errors.0'
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
                    'errors.children.price.errors.0'
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
                    'errors.children.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'errors.children.product.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
            /***********************************************************************************************
             * 'cause'
             ***********************************************************************************************/
            'not valid empty cause' => array(
                400,
                array('cause' => ''),
                array(
                    'errors.children.cause.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
            'not valid cause long 1001' => array(
                400,
                array('cause' => str_repeat('z', 1001)),
                array(
                    'errors.children.cause.errors.0'
                    =>
                        'Не более 1000 символов'
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
        );
    }

    public function testPostActionWriteOffNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);

        $postData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products',
            'POST',
            $postData
        );
        $this->client->jsonRequest($postRequest, $accessToken);

        $this->assertResponseCode(201);

        $invalidRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products',
            'POST',
            $postData
        );
        $this->client->setCatchException();
        $postResponse = $this->client->jsonRequest($invalidRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $postResponse);
    }

    public function testPutActionWriteOffProductNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.59, "Порча", $store->id);

        $putData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $this->client->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(200);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/invalidId',
            'PUT',
            $putData
        );

        $this->client->setCatchException();
        $putResponse = $this->client->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $this->client->setCatchException();
        $putResponse = $this->client->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $putResponse);

        $writeOffId2 = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 10, 5.99, 'Порча', $store->id);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $this->client->setCatchException();
        $putResponse = $this->client->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'PUT',
            $putData
        );
        $this->client->setCatchException();
        $putResponse = $this->client->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);
    }

    public function testDeleteActionWriteOffProductNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.59, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/invalidId',
            'DELETE'
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products/' . $writeOffProductId,
            'DELETE'
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId,
            'DELETE'
        );
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);
    }

    public function testGetWriteOffProductAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );
        $getResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOffProductId, 'id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId, 'writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
    }

    public function testGetWriteOffProductActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('1', null, $store->id);
        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId, 10, 5.59, 'Порча', $store->id);

        $writeOffId2 = $this->createWriteOff('2', null, $store->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 10, 5.59, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products/' . $writeOffProductId2
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId1
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidId/products/' . $writeOffProductId1
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidId/products/' . $writeOffProductId2
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products/invalidId'
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/invalidId'
        );
        $this->client->setCatchException();
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);
    }

    public function testProductAmountChangeOnWriteOf()
    {
        $storeId = $this->factory()->store()->getStoreId();

        $productId1 = $this->createProduct(1);
        $productId2 = $this->createProduct(2);

        $this->assertStoreProductTotals($storeId, $productId1, 0);

        $this->factory()
            ->invoice()
            ->createInvoice(array(), $storeId)
            ->createInvoiceProduct($productId1, 10, 4.99, $storeId)
            ->createInvoiceProduct($productId2, 20, 6.99, $storeId)
            ->flush();

        $this->assertStoreProductTotals($storeId, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        $writeOffId = $this->createWriteOff('431-678', null, $storeId);

        // create product 1 write off

        $postData = array(
            'product' => $productId1,
            'quantity' => 5,
            'price' => 3.49,
            'cause' => 'Порча',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffProductId1 = $postResponse['id'];

        $this->assertStoreProductTotals($storeId, $productId1, 5, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // change product 1 write off quantity

        $putData1 = array(
            'product' => $productId1,
            'quantity' => 7,
            'price' => 4.49,
            'cause' => 'Порча',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            'PUT',
            $putData1
        );
        $putResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($writeOffProductId1, 'id', $putResponse);

        $this->assertStoreProductTotals($storeId, $productId1, 3, 4.99);

        // write off product 2
        $putData2 = array(
            'product' => $productId2,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products',
            'POST',
            $putData2
        );
        $putResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $putResponse);

        $writeOffProductId2 = $putResponse['id'];

        $this->assertStoreProductTotals($storeId, $productId2, 16, 6.99);

        // Change product id

        $putData2 = array(
            'product' => $productId1,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'PUT',
            $putData2
        );
        $putResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, 'product.id', $putResponse);

        $this->assertStoreProductTotals($storeId, $productId1, -1, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // remove 2nd write off
        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'DELETE'
        );
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertStoreProductTotals($storeId, $productId1, 3, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // remove 1st write off
        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            'DELETE'
        );
        $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertStoreProductTotals($storeId, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);
    }

    public function testGetWriteOffProductsAction()
    {
        $store = $this->factory()->store()->getStore();

        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');

        $writeOffId1 = $this->createWriteOff('1', null, $store->id);
        $writeOffId2 = $this->createWriteOff('2', null, $store->id);

        $writeOffProduct1 = $this->createWriteOffProduct($writeOffId1, $product1, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct2 = $this->createWriteOffProduct($writeOffId1, $product2, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct3 = $this->createWriteOffProduct($writeOffId1, $product3, 10, 5.99, 'Порча', $store->id);

        $writeOffProduct4 = $this->createWriteOffProduct($writeOffId2, $product2, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct5 = $this->createWriteOffProduct($writeOffId2, $product3, 10, 5.99, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products');
        $getResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($writeOffProduct4, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct5, '*.id', $getResponse);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products');
        $getResponse = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($writeOffProduct1, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct3, '*.id', $getResponse);
    }

    public function testGetWriteOffProductsActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $store = $this->factory()->store()->getStore();
        $writeOffId = $this->createWriteOff('1', null, $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products');
        $response = $this->client->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testProductDataDoesNotChangeInWriteOffAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);

        $this->assertProduct($productId, array('name' => 'Кефир 5%'));
    }

    public function testTwoProductVersionsInWriteoff()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $writeOffProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $writeOffProductsResponse);
        Assert::assertJsonPathEquals($productId, '*.product.id', $writeOffProductsResponse, 2);
        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeOffProductsResponse, 1);
        Assert::assertJsonPathEquals('Кефир 5%', '*.product.name', $writeOffProductsResponse, 1);
    }

    public function testTwoProductVersionsCreated()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $this->updateProduct($productId, array('name' => 'Кефир 5%'));

        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        /* @var VersionRepository $productVersionRepository*/
        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);

        $this->assertCount(2, $productVersions);
    }

    /**
     * @dataProvider departmentManagerCanNotAccessWriteOffFromAnotherStoreProvider
     * @param string $method
     * @param string $url
     * @param int $expectedCode
     * @param array|null $data
     */
    public function testDepartmentManagerCanNotAccessWriteOffFromAnotherStore(
        $method,
        $url,
        $expectedCode,
        array $data = null
    ) {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('431-1111', null, $store1->id);
        $writeOffId2 = $this->createWriteOff('432-2222', null, $store2->id);

        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId, 2, 20, 'Бой', $store1->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 1, 10, 'Порча', $store2->id);

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        if (null !== $data) {
            $data += array(
                'product' => $productId,
                'price' => 7.99,
                'quantity' => 2,
                'cause' => 'Сгнил товар'
            );
        }

        $url1 = strtr(
            $url,
            array(
                '{store}' => $store1->id,
                '{writeOff}' => $writeOffId1,
                '{writeOffProduct}' => $writeOffProductId1
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
                '{writeOff}' => $writeOffId2,
                '{writeOffProduct}' => $writeOffProductId2
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
                '/api/1/stores/{store}/writeoffs/{writeOff}/products',
                200
            ),
            'POST' => array(
                'POST',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products',
                201,
                array()
            ),
            'GET::{writeOffProduct}' => array(
                'GET',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                200
            ),
            'PUT::{writeOffProduct}' => array(
                'PUT',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                200,
                array()
            ),
            'DELETE::{writeOffProduct}' => array(
                'DELETE',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                204
            ),
        );
    }

    public function testGetWriteOffProductNotFoundFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('431', null, $store1->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 2, 19.25, 'Порча', $store1->id);

        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);
        $accessToken = $this->factory()->oauth()->auth($departmentManager);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );

        $this->assertResponseCode(200);
    }

    public function testGetProductWriteOffProducts()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'purchasePrice' => 42.15));

        $writeOffId1 = $this->createWriteOff('MU-866', '2013-10-18T09:39:47+0400', $store->id);

        $writeOffProductId11 = $this->createWriteOffProduct($writeOffId1, $productId1, 100, 36.70, 'Бой', $store->id);
        $writeOffProductId13 = $this->createWriteOffProduct($writeOffId1, $productId3, 20, 42.90, 'Бой', $store->id);

        $writeOffId2 = $this->createWriteOff('MU-864', '2013-10-18T12:22:00+0400', $store->id);
        $writeOffProductId21 = $this->createWriteOffProduct($writeOffId2, $productId1, 120, 37.20, 'Бой', $store->id);
        $writeOffProductId22 = $this->createWriteOffProduct($writeOffId2, $productId2, 200, 35.80, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProductId13, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProductId22, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProductId11, '1.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProductId21, '0.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId1, '1.writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId2, '0.writeOff.id', $getResponse);
        Assert::assertNotJsonHasPath('*.store', $getResponse);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse);
    }

    public function testWriteOffProductTotalPriceWithFloatQuantity()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'purchasePrice' => 42.15));

        $writeOffId1 = $this->createWriteOff('MU-866', '2013-10-18T09:39:47+0400', $store->id);

        $this->createWriteOffProduct($writeOffId1, $productId1, 99.99, 36.78, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId2, 0.4, 21.77, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId3, 7.77, 42.99, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(36.78, "*.price", $getResponse);
        Assert::assertJsonPathEquals(99.99, "*.quantity", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId2 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(0.4, "*.quantity", $getResponse);
        Assert::assertJsonPathEquals(21.77, "*.price", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId3 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(42.99, "*.price", $getResponse);
        Assert::assertJsonPathEquals(7.77, "*.quantity", $getResponse);
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
            "/api/1/writeoffs/{$writeOff->id}",
            $putData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function testProductsActionCategoryIsNotExposed()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');
        $writeOffId = $this->createWriteOff('1', null, $storeId);
        $this->createWriteOffProduct($writeOffId, $productId1, 2, 5.99, 'Порча', $storeId);
        $this->createWriteOffProduct($writeOffId, $productId2, 1, 6.99, 'Порча', $storeId);
        $this->createWriteOffProduct($writeOffId, $productId3, 3, 2.59, 'Порча', $storeId);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products'
        );
        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('*.product.subCategory', $getResponse);
        Assert::assertJsonHasPath('*.writeOff', $getResponse);
        Assert::assertNotJsonHasPath('*.product.subCategory.category.group', $getResponse);
        Assert::assertJsonPathCount(0, '*.writeOff.products.*.id', $getResponse);
        Assert::assertNotJsonHasPath('*.product.subCategory.category', $getResponse);
    }
}
