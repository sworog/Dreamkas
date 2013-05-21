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

        $writeOfId = $this->createWriteOff();

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);

        Assert::assertJsonPathContains('not found', '*.message', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertNotJsonHasPath('number', $getResponse);
        Assert::assertNotJsonHasPath('date', $getResponse);
    }
}
