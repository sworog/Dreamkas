<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->setUpStoreDepartmentManager();
    }
    
    public function testPostAction()
    {
        $date = strtotime('-1 day');

        $writeOffData = array(
            'number' => '431-5678',
            'date' => date('c', $date),
        );

        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
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
        $writeOffData = $data + array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
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
        $postData = array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId,
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
        $number = '431-1234';
        $date = '2012-05-23T15:12:05+0400';

        $writeOfId = $this->createWriteOff($number, $date, $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOfId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOfId, 'id', $getResponse);
        Assert::assertJsonPathEquals($number, 'number', $getResponse);
        Assert::assertJsonPathEquals($date, 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $this->createWriteOff('431', null, $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/invalidId'
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
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $writeOffId = $this->createWriteOff('431', null, $this->storeId, $this->departmentManager);

        $this->assertWriteOff($writeOffId, array('itemsCount' => null, 'sumTotal' => null));

        $writeOffProductId1 = $this->createWriteOffProduct(
            $writeOffId,
            $productId1,
            12,
            5.99,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );

        $this->assertWriteOff($writeOffId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        $writeOffProductId2 = $this->createWriteOffProduct(
            $writeOffId,
            $productId2,
            3,
            6.49,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );

        $this->assertWriteOff($writeOffId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        $writeOffProductId3 = $this->createWriteOffProduct(
            $writeOffId,
            $productId3,
            1,
            11.12,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st write off product quantity and price

        $putData = array(
            'product' => $productId1,
            'price' => 6.99,
            'quantity' => 10,
            'cause' => 'because',
        );

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            $putData
        );

        $this->assertResponseCode(200);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

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
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            $putData
        );

        $this->assertResponseCode(200);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId3
        );

        $this->assertResponseCode(204);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 2, 'sumTotal' => 89.37));
    }

    /**
     * @param string $writeOffId
     * @param array $assertions
     */
    protected function assertWriteOff($writeOffId, array $assertions = array())
    {
        $accessToken = $this->auth($this->departmentManager);

        $writeOffJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($writeOffJson, $assertions);
    }

    public function testGetWriteOffsAction()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $writeOffId = $this->createWriteOff('4312', null, $this->storeId, $this->departmentManager);
        $this->createWriteOffProduct(
            $writeOffId,
            $productId1,
            12,
            5.99,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId,
            $productId2,
            3,
            6.49,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId,
            $productId3,
            1,
            11.12,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );

        $writeOffId2 = $this->createWriteOff('2', null, $this->storeId, $this->departmentManager);
        $this->createWriteOffProduct(
            $writeOffId2,
            $productId1,
            1,
            6.92,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId2,
            $productId2,
            2,
            3.49,
            'Порча',
            $this->storeId,
            $this->departmentManager
        );

        $accessToken = $this->auth($this->departmentManager);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs'
        );

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($writeOffId, '*.id', $response, 1);
        Assert::assertJsonPathEquals($writeOffId2, '*.id', $response, 1);
    }

    public function testDepartmentManagerCantGetWriteOffsFromAnotherStore()
    {
        $storeId2 = $this->createStore('43');
        $departmentManager2 = $this->createUser('Депардье Ж.К.М.', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $this->linkDepartmentManagers($storeId2, $departmentManager2->id);

        $accessToken1 = $this->auth($this->departmentManager);
        $accessToken2 = $this->auth($departmentManager2);

        $writeOffId1 = $this->createWriteOff('4313', null, $this->storeId, $this->departmentManager);
        $writeOffId2 = $this->createWriteOff('4314', null, $storeId2, $departmentManager2);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId1
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $storeId2 . '/writeoffs/' . $writeOffId2
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId1
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $storeId2 . '/writeoffs/' . $writeOffId2
        );

        $this->assertResponseCode(200);
    }

    public function testGetWriteOffNotFoundInAnotherStore()
    {
        $storeId2 = $this->createStore('43');
        $this->linkDepartmentManagers($storeId2, $this->departmentManager->id);

        $writeOffId = $this->createWriteOff('444', null, $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/writeoffs/' . $writeOffId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs/' . $writeOffId
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
        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');
        $productId3 = $this->createProduct('333');

        $writeOffData1 = array(
            'number' => '1234-89',
        );

        $writeOffId1 = $this->createWriteOff($writeOffData1['number'], null, $this->storeId, $this->departmentManager);
        $this->createWriteOffProduct(
            $writeOffId1,
            $productId1,
            10,
            6.98,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $writeOffData2 = array(
            'number' => '866-89',
        );

        $writeOffId2 = $this->createWriteOff($writeOffData2['number'], null, $this->storeId, $this->departmentManager);
        $this->createWriteOffProduct(
            $writeOffId2,
            $productId2,
            5,
            10.12,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $writeOffData3 = array(
            'number' => '7561-89',
        );

        $writeOffId3 = $this->createWriteOff($writeOffData3['number'], null, $this->storeId, $this->departmentManager);
        $this->createWriteOffProduct(
            $writeOffId3,
            $productId3,
            7,
            67.32,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
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
        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');

        $writeOffData1 = array(
            'number' => '1234-89',
            'date' => '2013-03-17T16:12:33+0400',
        );

        $writeOffId1 = $this->createWriteOff(
            $writeOffData1['number'],
            $writeOffData1['date'],
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId1,
            $productId1,
            10,
            6.98,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $writeOffData2 = array(
            'number' => '1234-89',
            'date' => '2013-03-16T14:54:23+0400'
        );

        $writeOffId2 = $this->createWriteOff(
            $writeOffData2['number'],
            $writeOffData2['date'],
            $this->storeId,
            $this->departmentManager
        );

        $this->createWriteOffProduct(
            $writeOffId2,
            $productId2,
            5,
            10.12,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
            null,
            array('number' => '1234-89')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('1234-89', '0.number', $response);
        Assert::assertJsonPathEquals('2013-03-17T16:12:33+0400', '0.date', $response);
        Assert::assertJsonPathEquals('1234-89', '1.number', $response);
        Assert::assertJsonPathEquals('2013-03-16T14:54:23+0400', '1.date', $response);

        $writeOffData3 = array(
            'number' => '1235-89',
            'date' => '2013-03-15T16:12:33+0400'
        );

        $writeOffId3 = $this->createWriteOff(
            $writeOffData3['number'],
            $writeOffData3['date'],
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId3,
            $productId1,
            10,
            6.98,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $writeOffData4 = array(
            'number' => '867-89',
            'date' => '2013-03-16T14:54:23+0400'
        );

        $writeOffId4 = $this->createWriteOff(
            $writeOffData4['number'],
            $writeOffData4['date'],
            $this->storeId,
            $this->departmentManager
        );
        $this->createWriteOffProduct(
            $writeOffId4,
            $productId2,
            5,
            10.12,
            null,
            $this->storeId,
            $this->departmentManager
        );

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/writeoffs',
            null,
            array('number' => '1235-89')
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
