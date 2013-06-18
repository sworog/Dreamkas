<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testPostUserUniqueUsernameTest()
    {
        $this->clearMongoDb();


        $userData = array(
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

        Assert::assertResponseCode(201, $this->client);

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains('Пользователь с таким логином уже существует', 'children.username.errors.0', $response);
    }

    /**
     * @dataProvider userValidationProvider
     */
    public function testPostUserActionValidation(
        $expectedCode,
        array $data,
        array $assertions = array()
    ) {
        $this->clearMongoDb();

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
            'valid username symbols 1' => array(
                201,
                array('username' => '1234567890'),
            ),
            'valid username symbols 2' => array(
                201,
                array('username' => '@asdffdd'),
            ),
            'valid username symbols 3' => array(
                201,
                array('username' => 'q.wer'),
            ),

            'valid username symbols 4' => array(
                201,
                array('username' => 'фыва'),
            ),
            'not valid username symbols' => array(
                400,
                array('username' => 'фa1234567890-_=][\';/.,.|+_)()*&^%$#@!{}:"'),
                array(
                    'children.username.errors.0'
                    =>
                    'Значение недопустимо.'
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
                    'Выбранное Вами значение недопустимо.'
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
                array('password' => str_repeat('z', 5)),
                array(
                    'children.password.errors.0'
                    =>
                    'Значение слишком короткое. Должно быть равно 6 символам или больше.'
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
                array('password' => 'userer', 'username' => 'userer'),
                array(
                    'children.password.errors.0'
                    =>
                    'Логин и пароль не должный совпадать'
                )
            ),
        );
    }
}
