<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class OrganizationControllerTest extends WebTestCase
{
    /**
     * @dataProvider postActionProvider
     * @param array $postData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPostAction(array $postData, $expectedCode, array $assertions)
    {
        $user = $this->factory()->user()->createProjectUser();

        $accessToken = $this->factory()->oauth()->auth($user);

        $postData += array(
            'name' => '',
            'phone' => '',
            'fax' => '',
            'email' => '',
            'director' => '',
            'chiefAccountant' => '',
            'address' => '',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);

        if (201 === $expectedCode) {
            Assert::assertJsonHasPath('id', $postResponse);

            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/organizations/' . $postResponse['id']
            );

            $this->assertSame($postResponse, $getResponse);
        }
    }

    /**
     * @return array
     */
    public function postActionProvider()
    {
        return array(
            'only valid name' => array(
                array(
                    'name' => 'ООО "Каннибал"',
                ),
                201,
                array(
                    'name' => 'ООО "Каннибал"',
                )
            ),
            'all data filled' => array(
                array(
                    'name' => 'ИП "Борисов"',
                    'phone' => '+79023456789',
                    'fax' => '(812) 234-09-88',
                    'email' => 'boris.ov@list.ru',
                    'director' => 'Борисов Иван Петрович',
                    'chiefAccountant' => 'Борисова Надежда Ильинишна',
                    'address' => 'Борисполь, ул. Ленина д.38',
                ),
                201,
                array(
                    'name' => 'ИП "Борисов"',
                    'phone' => '+79023456789',
                    'fax' => '(812) 234-09-88',
                    'email' => 'boris.ov@list.ru',
                    'director' => 'Борисов Иван Петрович',
                    'chiefAccountant' => 'Борисова Надежда Ильинишна',
                    'address' => 'Борисполь, ул. Ленина д.38',
                ),
            ),
            'missing name' => array(
                array(
                    'name' => '',
                ),
                400,
                array(
                    'children.name.errors.0' => 'Заполните это поле',
                )
            ),
            'length validation valid' => array(
                array(
                    'name' => str_repeat('n', 300),
                    'phone' => str_repeat('p', 300),
                    'fax' => str_repeat('f', 300),
                    'email' => str_repeat('e', 300),
                    'director' => str_repeat('p', 300),
                    'chiefAccountant' => str_repeat('c', 300),
                    'address' => str_repeat('a', 300),
                ),
                201,
                array()
            ),
            'length validation invalid' => array(
                array(
                    'name' => str_repeat('n', 301),
                    'phone' => str_repeat('p', 301),
                    'fax' => str_repeat('f', 301),
                    'email' => str_repeat('e', 301),
                    'director' => str_repeat('p', 301),
                    'chiefAccountant' => str_repeat('c', 301),
                    'address' => str_repeat('a', 301),
                ),
                400,
                array(
                    'children.name.errors.0' => 'Не более 300 символов',
                    'children.phone.errors.0' => 'Не более 300 символов',
                    'children.fax.errors.0' => 'Не более 300 символов',
                    'children.email.errors.0' => 'Не более 300 символов',
                    'children.director.errors.0' => 'Не более 300 символов',
                    'children.chiefAccountant.errors.0' => 'Не более 300 символов',
                    'children.address.errors.0' => 'Не более 300 символов',
                )
            ),
        );
    }

    /**
     * @dataProvider postActionProvider
     * @param array $putData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPutAction(array $putData, $expectedCode, array $assertions)
    {
        $user = $this->factory()->user()->createProjectUser();

        $accessToken = $this->factory()->oauth()->auth($user);

        $postData = array(
            'name' => 'Колян'
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $putData += array(
            'name' => '',
            'phone' => '',
            'fax' => '',
            'email' => '',
            'director' => '',
            'chiefAccountant' => '',
            'address' => '',
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/organizations/' . $id,
            $putData
        );

        $expectedCode = (201 === $expectedCode) ? 200 : $expectedCode;
        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 === $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/organizations/' . $postResponse['id']
            );

            $this->assertSame($putResponse, $getResponse);
        }
    }
}
