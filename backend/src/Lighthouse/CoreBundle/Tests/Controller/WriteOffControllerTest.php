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

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $writeOffData)
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

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $writeOffData)
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

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '.json',
            array('writeOff' => $putData)
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

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOfId . '.json'
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

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);

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

        $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1 . '.json',
            array('writeOffProduct' => $putData)
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
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2 . '.json',
            array('writeOffProduct' => $putData)
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertWriteOff($writeOffId, array('itemsCount' => 3, 'sumTotal' => 100.49));

        // remove 3rd write off product

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId3 . '.json'
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
        $writeOffJson = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId. '.json'
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
        $writeOffProductId11 = $this->createWriteOffProduct($writeOffId, $productId1, 5.99, 12);
        $writeOffProductId12 = $this->createWriteOffProduct($writeOffId, $productId2, 6.49, 3);
        $writeOffProductId13 = $this->createWriteOffProduct($writeOffId, $productId3, 11.12, 1);

        $writeOffId2 = $this->createWriteOff('2');
        $writeOffProductId21 = $this->createWriteOffProduct($writeOffId2, $productId1, 6.92, 1);
        $writeOffProductId22 = $this->createWriteOffProduct($writeOffId2, $productId2, 3.49, 2);

        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs.json'
        );

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($writeOffId, '*.id', $response, 1);
        Assert::assertJsonPathEquals($writeOffId2, '*.id', $response, 1);
    }
}
