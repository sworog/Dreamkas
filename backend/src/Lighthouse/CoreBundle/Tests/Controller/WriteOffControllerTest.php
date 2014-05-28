<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $date = strtotime('-1 day');

        $writeOffData = array(
            'number' => '431-5678',
            'date' => date('c', $date),
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/writeoffs',
            $writeOffData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertNotJsonHasPath('products.*.product', $postResponse);
        Assert::assertJsonPathEquals($writeOffData['number'], 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
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
        $writeOffData = $data + array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/writeoffs',
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

    public function validationWriteOffProvider()
    {
        return array(
            'not valid empty date' => array(
                400,
                array('date' => ''),
                array(
                    'children.date.errors.0'
                    =>
                    'Заполните это поле'
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
                    'children.date.errors.0'
                    =>
                    'Вы ввели неверную дату 2013-2sd-31, формат должен быть следующий дд.мм.гггг'
                )
            ),
            'not valid empty number' => array(
                400,
                array('number' => ''),
                array(
                    'children.number.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 101 number' => array(
                400,
                array('number' => str_repeat('z', 101)),
                array(
                    'children.number.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'valid long 100 number' => array(
                201,
                array('number' => str_repeat('z', 100)),
            ),
        );
    }

    public function testGetAction()
    {
        $store = $this->factory()->store()->getStore();

        $number = '431-1234';
        $date = '2012-05-23T15:12:05+0400';

        $writeOfId = $this->createWriteOff($number, $date, $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOfId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOfId, 'id', $getResponse);
        Assert::assertJsonPathEquals($number, 'number', $getResponse);
        Assert::assertJsonPathEquals($date, 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $this->createWriteOff('431', null, $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/invalidId'
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

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId1
        );

        $this->assertResponseCode(403);

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
}
