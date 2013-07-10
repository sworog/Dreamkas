<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $this->clearMongoDb();

        $date = strtotime('-1 day');

        $writeOffData = array(
            'number' => '431-5678',
            'date' => date('c', $date),
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeoffs',
            $writeOffData
        );

        Assert::assertResponseCode(201, $this->client);

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
        $this->clearMongoDb();

        $writeOffData = $data + array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeoffs',
            $writeOffData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

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
        $this->clearMongoDb();

        $postData = array(
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeoffs',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId,
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

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
        $this->clearMongoDb();

        $number = '431-1234';
        $date = '2012-05-23T15:12:05+0400';

        $writeOfId = $this->createWriteOff($number, $date);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/' . $writeOfId
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($writeOfId, 'id', $getResponse);
        Assert::assertJsonPathEquals($number, 'number', $getResponse);
        Assert::assertJsonPathEquals($date, 'date', $getResponse);
    }

    public function testGetActionNotFound()
    {
        $this->clearMongoDb();

        $this->createWriteOff();

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/invalidId'
        );

        Assert::assertResponseCode(404, $this->client);

        // There is not message in debug=false mode
        Assert::assertJsonPathContains('not found', 'message', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertNotJsonHasPath('number', $getResponse);
        Assert::assertNotJsonHasPath('date', $getResponse);
    }

    public function testWriteOffTotals()
    {
        $this->clearMongoDb();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $writeOffId = $this->createWriteOff();

        $this->assertWriteOff($writeOffId, array('itemsCount' => null, 'sumTotal' => null));

        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId, $productId1, 5.99, 12);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 1, 'sumTotal' => 71.88));

        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId, $productId2, 6.49, 3);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 2, 'sumTotal' => 91.35));

        $writeOffProductId3 = $this->createWriteOffProduct($writeOffId, $productId3, 11.12, 1);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 102.47));

        // update 1st write off product quantity and price

        $putData = array(
            'product' => $productId1,
            'price' => 6.99,
            'quantity' => 10,
            'cause' => 'because',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            $putData
        );

        Assert::assertResponseCode(200, $this->client);

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
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            $putData
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId3
        );

        Assert::assertResponseCode(204, $this->client);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 2, 'sumTotal' => 89.37));
    }

    /**
     * @param string $writeOffId
     * @param array $assertions
     */
    protected function assertWriteOff($writeOffId, array $assertions = array())
    {
        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $writeOffJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/' . $writeOffId
        );

        Assert::assertResponseCode(200, $this->client);

        $this->performJsonAssertions($writeOffJson, $assertions);
    }

    public function testGetWriteOffsAction()
    {
        $this->clearMongoDb();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $writeOffId = $this->createWriteOff();
        $this->createWriteOffProduct($writeOffId, $productId1, 5.99, 12);
        $this->createWriteOffProduct($writeOffId, $productId2, 6.49, 3);
        $this->createWriteOffProduct($writeOffId, $productId3, 11.12, 1);

        $writeOffId2 = $this->createWriteOff('2');
        $this->createWriteOffProduct($writeOffId2, $productId1, 6.92, 1);
        $this->createWriteOffProduct($writeOffId2, $productId2, 3.49, 2);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs'
        );

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($writeOffId, '*.id', $response, 1);
        Assert::assertJsonPathEquals($writeOffId2, '*.id', $response, 1);
    }
}
