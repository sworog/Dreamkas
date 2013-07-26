<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
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
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
            'password'  => 'qwerty',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode(201, $this->client);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Пользователь с таким логином уже существует',
            'children.username.errors.0',
            $response
        );
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
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
            'password'  => 'qwerty',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @dataProvider editUserValidationPasswordProvider
     */
    public function testPutUserAction(
        $expectedCode,
        array $data,
        array $assertions = array()
    ) {
        $this->clearMongoDb();

        $userData = array(
            'username'  => 'qweqwe',
            'name'      => 'ASFFS',
            'position'  => 'SFwewe',
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
            'password'  => 'qwerty',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];

        $newUserData = $data + array(
            'username'  => 'васяпупкин',
            'name'      => 'Вася Пупкин',
            'position'  => 'Комец бля',
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/users/' . $id,
            $newUserData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;
        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    public function testPasswordChange()
    {
        $this->clearMongoDb();

        $userData = array(
            'username'  => 'qweqwe',
            'name'      => 'ASFFS',
            'position'  => 'SFwewe',
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
            'password'  => 'qwerty',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('lighthouse.core.document.repository.user');
        $userModel = $userRepository->find($id);

        $oldPasswordHash = $userModel->password;

        $newUserData = array(
            'username'  => 'qweqwe',
            'name'      => 'ASFFSssd',
            'position'  => 'SFwewe',
            'role'      => 'ROLE_COMMERCIAL_MANAGER',
            'password'  => '',
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/users/' . $id,
            $newUserData
        );

        Assert::assertResponseCode(200, $this->client);
        $userRepository->clear();
        $userModel = $userRepository->find($id);

        $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($userModel);
        $passwordHash = $encoder->encodePassword($userData['password'], $userModel->getSalt());

        $this->assertEquals($passwordHash, $userModel->password);
        $this->assertEquals($oldPasswordHash, $userModel->password);
    }

    public function editUserValidationPasswordProvider()
    {
        return $this->editUserValidationProvider() + array(
            /***********************************************************************************************
             * 'password'
             ***********************************************************************************************/
            'valid password' => array(
                200,
                array('password' => 'qwerty'),
            ),
            'valid password 100 chars' => array(
                200,
                array('password' => str_repeat('z', 100)),
            ),
            'valid password symbols' => array(
                200,
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
                200,
                array('password' => ''),
            ),
            'not valid password equals username' => array(
                400,
                array('password' => 'userer', 'username' => 'userer'),
                array(
                    'children.password.errors.0'
                    =>
                    'Логин и пароль не должны совпадать'
                )
            ),
        );
    }

    public function editUserValidationProvider()
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
                array('role' => 'ROLE_COMMERCIAL_MANAGER'),
            ),
            'valid role storeManager' => array(
                201,
                array('role' => 'ROLE_STORE_MANAGER'),
            ),
            'valid role departmentManager' => array(
                201,
                array('role' => 'ROLE_DEPARTMENT_MANAGER'),
            ),
            'valid role administrator' => array(
                201,
                array('role' => 'ROLE_ADMINISTRATOR'),
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
        );
    }

    public function userValidationProvider()
    {
        return $this->editUserValidationProvider() + array(
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
                    'Логин и пароль не должны совпадать'
                )
            ),
        );
    }

    /**
     * @dataProvider userProvider
     */
    public function testGetUsersAction(array $data)
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $postDataArray = array();
        for ($i = 0; $i < 5; $i++) {
            $postData = $data;
            $postData['name'] .= $i;
            $postData['username'] .= $i;
            $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/users',
                $postData
            );
            Assert::assertResponseCode(201, $this->client);
            $postDataArray[] = $postData;
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users'
        );
        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(6, '*.username', $postResponse);

        foreach ($postDataArray as $postData) {
            foreach ($postData as $key => $value) {
                if ($key != 'password') {
                    Assert::assertJsonPathEquals($value, '*.'.$key, $postResponse);
                }
            }
        }
    }

    /**
     * @dataProvider getUsersActionPermissionDeniedProvider
     */
    public function testGetUsersActionPermissionDenied($role)
    {
        $this->clearMongoDb();

        $this->createUser('user1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->createUser('user2', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $this->createUser('user3', 'password', User::ROLE_STORE_MANAGER);
        $this->createUser('user4', 'password', User::ROLE_ADMINISTRATOR);

        $accessToken = $this->authAsRole($role);

        $this->clientJsonRequest($accessToken, 'GET', '/api/1/users');

        $this->assertResponseCode(403);
    }

    /**
     * @return array
     */
    public function getUsersActionPermissionDeniedProvider()
    {
        return array(
            array(User::ROLE_COMMERCIAL_MANAGER),
            array(User::ROLE_STORE_MANAGER),
            array(User::ROLE_DEPARTMENT_MANAGER),
        );
    }

    /**
     * @dataProvider userProvider
     */
    public function testGetUserAction(array $postData)
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $postData
        );
        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $id
        );
        Assert::assertResponseCode(200, $this->client);
        foreach ($postData as $key => $value) {
            if ($key != 'password') {
                Assert::assertJsonPathEquals($value, $key, $postResponse);
            }
        }
    }

    public function testGetUsersCurrentAction()
    {
        $this->clearMongoDb();

        $authClient = $this->createAuthClient();
        $user = $this->createUser('user', 'qwerty123');

        $token = $this->auth($user, 'qwerty123', $authClient);

        $request = new JsonRequest('/api/1/users/current');
        $request->addHttpHeader('Authorization', 'Bearer ' . $token->access_token);

        $getResponse = $this->jsonRequest($request);

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals('user', 'username', $getResponse);
    }

    public function testGetUserPermissionsAction()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/permissions'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathContains('GET::{user}', 'users', $response);
    }

    public function userProvider()
    {
        return array(
            'standard' => array(
                array(
                    'username'  => 'test',
                    'name'      => 'Вася пупкин',
                    'position'  => 'Комерческий директор',
                    'role'      => 'ROLE_COMMERCIAL_MANAGER',
                    'password'  => 'qwerty',
                ),
            ),
        );
    }
}
