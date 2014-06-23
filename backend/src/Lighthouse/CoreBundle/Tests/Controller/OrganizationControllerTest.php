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
        );
    }
}
