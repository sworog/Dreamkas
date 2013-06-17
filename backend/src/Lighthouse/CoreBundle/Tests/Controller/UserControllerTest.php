<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @dataProvider userValidationProvider
     */
    public function testPostUserActionValidation(
        $expectedCode,
        array $data,
        array $assertions = array()
    ) {
        $userData = $data + array(
            'username'  => 'test',
            'name'      => 'Вася пупкин',
            'position'  => 'Комерческий директор',
            'role'      => 'commercialManager',
            'password'  => 'qwerty',
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    public function userValidationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'name'
             ***********************************************************************************************/
            'valid name' => array(
                201,
                array('name' => 'test'),
            ),
            'valid name 100 chars' => array(
                201,
                array('name' => str_repeat('z', 100)),
            ),
            'valid name symbols' => array(
                201,
                array('name' => 'фa1234567890-_=][\';/.,.|+_)()*&^%$#@!{}:"'),
            ),
            'not valid name 101 chars' => array(
                400,
                array('name' => str_repeat('z', 101)),
                array(
                    'children.name.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'empty name' => array(
                400,
                array('name' => ''),
                array(
                    'children.name.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            /***********************************************************************************************
             * 'username'
             ***********************************************************************************************/
            'valid username' => array(
                201,
                array('username' => 'test'),
            ),
            'valid username 100 chars' => array(
                201,
                array('username' => str_repeat('z', 100)),
            ),
            'valid username symbols' => array(
                201,
                array('username' => '1234567890-_aф@.'),
            ),
            'not valid username symbols' => array(
                400,
                array('username' => 'фa1234567890-_=][\';/.,.|+_)()*&^%$#@!{}:"'),
                array(
                    'children.username.errors.0'
                    =>
                    'Запрещенные символы в имени пользователя можно только ...'
                ),
            ),
            'not valid username 101 chars' => array(
                400,
                array('username' => str_repeat('z', 101)),
                array(
                    'children.username.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'empty username' => array(
                400,
                array('username' => ''),
                array(
                    'children.username.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            /***********************************************************************************************
             * 'position'
             ***********************************************************************************************/
            'valid position' => array(
                201,
                array('position' => 'test'),
            ),
            'valid position 100 chars' => array(
                201,
                array('position' => str_repeat('z', 100)),
            ),
            'valid position symbols' => array(
                201,
                array('position' => 'фa1234567890-_=][\';/.,.|+_)()*&^%$#@!{}:"'),
            ),
            'not valid position 101 chars' => array(
                400,
                array('position' => str_repeat('z', 101)),
                array(
                    'children.position.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'empty position' => array(
                400,
                array('position' => ''),
                array(
                    'children.position.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            /***********************************************************************************************
             * 'role'
             ***********************************************************************************************/
            'valid role commercialManager' => array(
                201,
                array('role' => 'commercialManager'),
            ),
            'valid role storeManager' => array(
                201,
                array('role' => 'storeManager'),
            ),
            'valid role departmentalManager' => array(
                201,
                array('role' => 'departmentalManager'),
            ),
            'valid role administrator' => array(
                201,
                array('role' => 'administrator'),
            ),
            'not valid role' => array(
                400,
                array('role' => 'govnar'),
                array(
                    'children.role.errors.0'
                    =>
                    'Такой роли не существует'
                )
            ),
            'empty role' => array(
                400,
                array('role' => ''),
                array(
                    'children.role.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            /***********************************************************************************************
             * 'password'
             ***********************************************************************************************/
            'valid password' => array(
                201,
                array('password' => 'qwerty'),
            ),
            'valid password 100 chars' => array(
                201,
                array('password' => str_repeat('z', 100)),
            ),
            'valid password symbols' => array(
                201,
                array('password' => 'фa1234567890-_=][\';/.,.|+_)()*&^%$#@!{}:"'),
            ),
            'not valid password 5 chars' => array(
                400,
                array('password' => str_repeat('z', 101)),
                array(
                    'children.password.errors.0'
                    =>
                    'Не менее 6 символов'
                )
            ),
            'empty password' => array(
                400,
                array('password' => ''),
                array(
                    'children.password.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid password equals username' => array(
                400,
                array('password' => 'user', 'username' => 'user'),
                array(
                    'children.password.errors.0'
                    =>
                    'Логин и пароль не должный совпадать'
                )
            ),
        );
    }
}
