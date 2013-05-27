<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class KlassControllerTest extends WebTestCase
{
    public function testPostKlassAction()
    {
        $this->clearMongoDb();

        $klassData = array(
            'name' => 'Продовольственные товары'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            'api/1/klasses.json',
            array('klass' => $klassData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonPathEquals('Продовольственные товары', 'name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationKlassProvider
     */
    public function testPostKlassValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $klassData = $data + array(
            'name' => 'Продовольственные товары'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            'api/1/klasses.json',
            array('klass' => $klassData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationKlassProvider()
    {
        return array(
            'not valid empty name' => array(
                400,
                array('name' => ''),
                array(
                    'children.name.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 101 name' => array(
                400,
                array('name' => str_repeat('z', 101)),
                array(
                    'children.name.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'valid long 100 name' => array(
                201,
                array('name' => str_repeat('z', 100)),
            ),
        );
    }
}
