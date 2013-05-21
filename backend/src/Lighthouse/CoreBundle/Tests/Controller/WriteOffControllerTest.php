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
}
