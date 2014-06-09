<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Factory\UserFactory;
use Lighthouse\CoreBundle\Test\WebTestCase;
use OAuth2\OAuth2ServerException;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use MongoDuplicateKeyException;
use Exception;

class UserControllerTest extends WebTestCase
{
    public function testPostUserUniqueUsernameTest()
    {
        $userData = array(
            'email'     => 'test@test.com',
            'name'      => 'Вася пупкин',
            'position'  => 'Комерческий директор',
            'roles'     => array('ROLE_COMMERCIAL_MANAGER'),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        $this->assertResponseCode(201);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Пользователь с таким email уже существует',
            'children.email.errors.0',
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
        $userData = $data + array(
            'email'     => 'test@test.com',
            'name'      => 'Вася пупкин',
            'position'  => 'Комерческий директор',
            'roles'      => array('ROLE_COMMERCIAL_MANAGER'),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

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
        $userData = array(
            'email'     => 'qwe@qwe.qwe',
            'name'      => 'ASFFS',
            'position'  => 'SFwewe',
            'roles'     => array(User::ROLE_COMMERCIAL_MANAGER),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];

        $newUserData = $data + array(
            'email'     => 'test@test.com',
            'name'      => 'Вася Пупкин',
            'position'  => 'Комец бля',
            'roles'     => array(User::ROLE_COMMERCIAL_MANAGER),
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/users/' . $id,
            $newUserData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;
        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    public function testPutCurrentUser()
    {
        $user = $this->factory()->user()->createUser(
            'user@lh.com',
            UserFactory::USER_DEFAULT_PASSWORD,
            User::getDefaultRoles()
        );

        $userData = array(
            'email' => 'loser@lh.com',
            'name'  => 'Loser LH',
            'password' => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->doAuthByUsername('user@lh.com');

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/users/current',
            $userData
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($user->id, 'id', $response);
        Assert::assertJsonPathEquals('loser@lh.com', 'email', $response);
        Assert::assertJsonPathEquals('Loser LH', 'name', $response);

        $this->factory()->clear();

        try {
            $this->factory()->oauth()->doAuthByUsername('user@lh.com');
            $this->fail();
        } catch (OAuth2ServerException $e) {
            $this->assertTrue(true);
        }

        $accessToken = $this->factory()->oauth()->doAuthByUsername('loser@lh.com', 'qwerty');
        $this->assertNotNull($accessToken->access_token);
    }

    public function testPutCurrentUserWithoutToken()
    {
        $userData = array(
            'email' => 'loser@lh.com',
            'name'  => 'Loser LH',
            'password' => 'qwerty',
        );

        $response = $this->clientJsonRequest(
            null,
            'PUT',
            '/api/1/users/current',
            $userData
        );

        $this->assertResponseCode(401);
        Assert::assertJsonPathEquals('access_denied', 'error', $response);
    }

    public function testPasswordChange()
    {
        $userData = array(
            'email'     => 'qweqwe@test.com',
            'name'      => 'ASFFS',
            'position'  => 'SFwewe',
            'roles'     => array(User::ROLE_COMMERCIAL_MANAGER),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('lighthouse.core.document.repository.user');
        $userModel = $userRepository->find($id);

        $oldPasswordHash = $userModel->password;

        $newUserData = array(
            'email'     => 'qweqwe@test.com',
            'name'      => 'ASFFSssd',
            'position'  => 'SFwewe',
            'roles'     => array(User::ROLE_COMMERCIAL_MANAGER),
            'password'  => '',
        );

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/users/' . $id,
            $newUserData
        );

        $this->assertResponseCode(200);
        $userRepository->clear();
        $userModel = $userRepository->find($id);

        /* @var EncoderFactoryInterface $encoderFactory */
        $encoderFactory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $encoderFactory->getEncoder($userModel);
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
                array('password' => 'userer@test.com', 'email' => 'userer@test.com'),
                array(
                    'children.password.errors.0' => 'Логин и пароль не должны совпадать'
                )
            ),
        );
    }

    public function editUserValidationProvider()
    {
        return $this->emailUserValidationProvider() + array(
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
                    'children.name.errors.0' => 'Не более 100 символов'
                )
            ),
            'empty name' => array(
                400,
                array('name' => ''),
                array(
                    'children.name.errors.0' => 'Заполните это поле',
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
                    'children.position.errors.0' => 'Не более 100 символов'
                )
            ),
            'empty position' => array(
                400,
                array('position' => ''),
                array(
                    'children.position.errors.0' => 'Заполните это поле',
                ),
            ),
            /***********************************************************************************************
             * 'roles'
             ***********************************************************************************************/
            'valid role commercialManager' => array(
                201,
                array('roles' => array('ROLE_COMMERCIAL_MANAGER')),
            ),
            'valid role storeManager' => array(
                201,
                array('roles' => array('ROLE_STORE_MANAGER')),
            ),
            'valid role departmentManager' => array(
                201,
                array('roles' => array('ROLE_DEPARTMENT_MANAGER')),
            ),
            'valid role administrator' => array(
                201,
                array('roles' => array('ROLE_ADMINISTRATOR')),
            ),
            'not valid role' => array(
                400,
                array('roles' => array('govnar')),
                array(
                    'children.roles.errors.0' => 'Значение недопустимо.'
                )
            ),
            'empty role' => array(
                400,
                array('roles' => ''),
                array(
                    'children.roles.errors.0' => 'Заполните это поле',
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function emailUserValidationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'email'
             ***********************************************************************************************/
            'valid email' => array(
                201,
                array('email' => 'test@test.com'),
            ),
            'valid email with dot' => array(
                201,
                array('email' => 'test.dot@test.com'),
            ),
            'valid email UPPERCASE' => array(
                201,
                array('email' => 'TEST@TEST.COM'),
            ),
            'not valid email for domain level 2' => array(
                400,
                array('email' => 'test@test'),
                array(
                    'children.email.errors.0' => 'Значение адреса электронной почты недопустимо.'
                ),
            ),
            'not valid email without at' => array(
                400,
                array('email' => 'test.test.com'),
                array(
                    'children.email.errors.0' => 'Значение адреса электронной почты недопустимо.'
                ),
            ),
            'not valid email wrong symbols' => array(
                400,
                array('email' => 'test[]@test.com'),
                array(
                    'children.email.errors.0' => 'Значение адреса электронной почты недопустимо.'
                )
            ),
            'not valid email !#$%&`*+\/=?^`{|}~@lighthouse.pro' => array(
                400,
                array('email' => '!#$%&`*+\/=?^`{|}~@lighthouse.pro'),
                array(
                    'children.email.errors.0' => 'Значение адреса электронной почты недопустимо.'
                )
            ),
            'empty email' => array(
                400,
                array('email' => ''),
                array(
                    'children.email.errors.0' => 'Заполните это поле',
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
            'not valid password equals email' => array(
                400,
                array('password' => 'userer@test.com', 'email' => 'userer@test.com'),
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
        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postDataArray = array();
        for ($i = 0; $i < 5; $i++) {
            $postData = $data;
            $postData['name'] .= $i;
            $postData['email'] .= $i;
            $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/users',
                $postData
            );
            $this->assertResponseCode(201);
            $postDataArray[] = $postData;
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users'
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(6, '*.email', $postResponse);

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
        $this->factory()->user()->getUser('user1@lighthouse.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->factory()->user()->getUser('user2@lighthouse.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $this->factory()->user()->getUser('user3@lighthouse.pro', 'password', User::ROLE_STORE_MANAGER);
        $this->factory()->user()->getUser('user4@lighthouse.pro', 'password', User::ROLE_ADMINISTRATOR);

        $accessToken = $this->factory()->oauth()->authAsRole($role);

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
        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $postData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $id
        );
        $this->assertResponseCode(200);
        foreach ($postData as $key => $value) {
            if ($key != 'password') {
                Assert::assertJsonPathEquals($value, $key, $postResponse);
            }
        }
    }

    public function testGetUsersCurrentAction()
    {
        $authClient = $this->factory()->oauth()->getAuthClient();
        $user = $this->factory()->user()->getUser('user@lighthouse.pro', 'qwerty123');

        $token = $this->factory()->oauth()->auth($user, 'qwerty123', $authClient);

        $request = new JsonRequest('/api/1/users/current');
        $request->addHttpHeader('Authorization', 'Bearer ' . $token->access_token);

        $getResponse = $this->jsonRequest($request);

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('user@lighthouse.pro', 'email', $getResponse);
    }

    public function testGetUserPermissionsAction()
    {
        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/permissions'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathContains('GET::{user}', 'users', $response);
    }

    public function userProvider()
    {
        return array(
            'standard' => array(
                array(
                    'email'     => 'test@test.com',
                    'name'      => 'Вася пупкин',
                    'position'  => 'Комерческий директор',
                    'roles'     => array('ROLE_COMMERCIAL_MANAGER'),
                    'password'  => 'qwerty',
                ),
            ),
        );
    }

    /**
     * @dataProvider rolePermissionsProvider
     * @param $role
     * @param array $assertions
     */
    public function testRolePermissions($role, array $assertions)
    {
        $accessToken = $this->factory()->oauth()->authAsRole($role);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/permissions'
        );

        $this->assertResponseCode(200);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEqualsArray($expected, $path, $getResponse);
        }
    }

    /**
     * @return array
     */
    public function rolePermissionsProvider()
    {
        return array(
            User::ROLE_DEPARTMENT_MANAGER => array(
                User::ROLE_DEPARTMENT_MANAGER,
                array(
                    'stores.*' => array(
                        'GET::{store}/products',
                        'GET::{store}/search/products',
                    ),
                    'stores/{store}/orders.*' => array(
                        'DELETE::{order}',
                        'GET',
                        'GET::{order}',
                        'GET::{order}/download',
                        'POST',
                        'POST::products',
                        'PUT::{order}',
                    ),
                    'stores/{store}/products/{product}.*' => array(
                        'GET',
                        'GET::invoiceProducts',
                        'GET::returnProducts',
                        'GET::writeOffProducts'
                    ),
                    'stores/{store}/groups.*' => array(
                        'GET::{group}/categories',
                        'GET'
                    ),
                    'stores/{store}/categories/{category}.*' => array(
                        'GET::subcategories',
                        'GET'
                    ),
                    'stores/{store}/subcategories/{subCategory}.*' => array(
                        'GET',
                        'GET::products',
                    ),
                    'stores/{store}/invoices.*' => array(
                        'GET::{invoice}',
                        'PUT::{invoice}',
                        'GET',
                        'POST'
                    ),
                    'stores/{store}/writeoffs.*' => array(
                        'GET::{writeOff}',
                        'PUT::{writeOff}',
                        'GET',
                        'POST'
                    ),
                    'stores/{store}/writeoffs/{writeOff}/products.*' => array(
                        'GET::{writeOffProduct}',
                        'PUT::{writeOffProduct}',
                        'DELETE::{writeOffProduct}',
                        'GET',
                        'POST'
                    ),
                    'users.*' => array(
                        'GET::current',
                        'GET::permissions',
                        'GET::{user}/stores'
                    ),
                    'suppliers.*' => array(
                        'GET',
                        'GET::{supplier}',
                    )
                )
            ),
            User::ROLE_STORE_MANAGER => array(
                User::ROLE_STORE_MANAGER,
                array(
                    'stores.*' => array(
                        'GET::{store}/products',
                        'GET::{store}/reports/grossMargin',
                        'GET::{store}/reports/grossSales',
                        'GET::{store}/reports/grossSalesByHours',
                        'GET::{store}/reports/grossSalesByGroups',
                    ),
                    'stores/{store}/products/{product}.*' => array(
                        'GET',
                        'PUT'
                    ),
                    'stores/{store}/groups.*' => array(
                        'GET::{group}/categories',
                        'GET',
                        'GET::{group}/reports/grossSalesByCategories',
                    ),
                    'stores/{store}/categories/{category}.*' => array(
                        'GET::subcategories',
                        'GET',
                        'GET::reports/grossSalesBySubCategories',
                    ),
                    'stores/{store}/subcategories/{subCategory}.*' => array(
                        'GET',
                        'GET::products',
                        'GET::reports/grossSalesByProducts',
                    ),
                    'stores/{store}/invoices.*' => array(),
                    'stores/{store}/writeoffs.*' => array(),
                    'stores/{store}/writeoffs/{writeOff}/products.*' => array(),
                    'users.*' => array(
                        'GET::current',
                        'GET::permissions',
                        'GET::{user}/stores'
                    ),
                    'suppliers.*' => array(
                        'GET',
                        'GET::{supplier}',
                    )
                )
            ),
            User::ROLE_COMMERCIAL_MANAGER => array(
                User::ROLE_COMMERCIAL_MANAGER,
                array(
                    'stores.*' => array(
                        'GET',
                        'GET::{store}',
                        'GET::{store}/departmentManagers',
                        'GET::{store}/departments',
                        'GET::{store}/storeManagers',
                        'LINK::{store}',
                        'POST',
                        'PUT::{store}',
                        'UNLINK::{store}'
                    ),
                    'stores/{store}/products/{product}.*' => array(),
                    'stores/{store}/groups.*' => array(),
                    'stores/{store}/categories/{category}.*' => array(),
                    'stores/{store}/subcategories/{subCategory}.*' => array(),
                    'stores/{store}/invoices.*' => array(),
                    'stores/{store}/writeoffs.*' => array(),
                    'stores/{store}/writeoffs/{writeOff}/products.*' => array(),
                    'users.*' => array(
                        'GET::current',
                        'GET::permissions',
                        'GET::{user}'
                    ),
                    'suppliers.*' => array(
                        'GET',
                        'GET::{supplier}',
                        'POST',
                        'PUT::{supplier}',
                    )
                )
            ),
            User::ROLE_ADMINISTRATOR => array(
                User::ROLE_ADMINISTRATOR,
                array(
                    'stores.*' => array(),
                    'stores/{store}/products/{product}.*' => array(),
                    'stores/{store}/groups.*' => array(),
                    'stores/{store}/categories/{category}.*' => array(),
                    'stores/{store}/subcategories/{subCategory}.*' => array(),
                    'stores/{store}/invoices.*' => array(),
                    'stores/{store}/writeoffs.*' => array(),
                    'stores/{store}/writeoffs/{writeOff}/products.*' => array(),
                    'users.*' => array(
                        'GET::current',
                        'GET::permissions',
                        'GET',
                        'GET::{user}',
                        'POST',
                        'PUT::{user}'
                    )
                )
            )
        );
    }

    /**
     * @group unique
     */
    public function testUniqueUsernameInParallel()
    {
        $userData = array(
            'email'     => 'test@test.com',
            'name'      => 'Вася пупкин',
            'position'  => 'Комерческий директор',
            'roles'      => array(User::ROLE_COMMERCIAL_MANAGER),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_ADMINISTRATOR);

        $jsonRequest = new JsonRequest('/api/1/users', 'POST', $userData);
        $jsonRequest->setAccessToken($accessToken);

        $responses = $this->client->parallelJsonRequest($jsonRequest, 3);
        $statusCodes = array();
        $jsonResponses = array();
        foreach ($responses as $response) {
            $statusCodes[] = $response->getStatusCode();
            $jsonResponses[] = $this->client->decodeJsonResponse($response);
        }
        $exporter = new Exporter();
        $responseBody = $exporter->export($jsonResponses);
        $this->assertCount(1, array_keys($statusCodes, 201), $responseBody);
        $this->assertCount(2, array_keys($statusCodes, 400), $responseBody);
        Assert::assertJsonPathEquals('test@test.com', '*.email', $jsonResponses, 1);
        Assert::assertJsonPathEquals(
            'Пользователь с таким email уже существует',
            '*.children.email.errors.0',
            $jsonResponses,
            2
        );
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $userData = array(
            'email'     => 'test@test.com',
            'name'      => 'Вася пупкин',
            'position'  => 'Комерческий директор',
            'roles'      => array(User::ROLE_COMMERCIAL_MANAGER),
            'password'  => 'qwerty',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_ADMINISTRATOR);

        $user = new User();

        $documentManagerMock = $this->getMock(
            'Doctrine\\ODM\\MongoDB\\DocumentManager',
            array(),
            array(),
            '',
            false
        );
        $documentManagerMock
            ->expects($this->once())
            ->method('persist');

        $documentManagerMock
            ->expects($this->once())
            ->method('flush')
            ->with($this->isEmpty())
            ->will($this->throwException($exception));

        $userRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\User\\UserRepository',
            array(),
            array(),
            '',
            false
        );

        $userRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($user));
        $userRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($userRepoMock) {
                $container->set('lighthouse.core.document.repository.user', $userRepoMock);
            }
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/users',
            $userData
        );

        return $response;
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedException()
    {
        $exception = new Exception('Unknown exception');
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(500);
        Assert::assertJsonPathEquals('Unknown exception', 'message', $response);
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedMongoDuplicateKeyException()
    {
        $exception = new MongoDuplicateKeyException();
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Validation Failed', 'message', $response);
        Assert::assertJsonPathEquals(
            'Пользователь с таким email уже существует',
            'children.email.errors.0',
            $response
        );
    }

    public function testPostSignupAction()
    {
        $postData = array(
            'email' => 'signup@lh.pro'
        );

        $response = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($postData['email'], 'email', $response);
        Assert::assertNotJsonHasPath('password', $response);
    }

    /**
     * @dataProvider emailUserValidationProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostSignupValidationTest(
        $expectedCode,
        array $data,
        array $assertions = array()
    ) {
        $postData = $data + array(
            'email' => 'signup@lh.pro',
        );

        $response = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions, false);
    }

    public function testPostSignupUniqueEmailValidationTest()
    {
        $userData = array(
            'email' => 'signup@lh.com',
        );

        $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $userData
        );

        $this->assertResponseCode(201);

        $response = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $userData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Пользователь с таким email уже существует',
            'children.email.errors.0',
            $response
        );
    }

    public function testPostSignupEmailSendAndLoginWithPassword()
    {
        $userData = array(
            'email' => 'signup@lh.com',
        );

        $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $userData
        );

        $this->assertResponseCode(201);

        $collectedMessages = $this->getSentEmailMessages();
        $message = $collectedMessages[0];

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('noreply@lighthouse.pro', key($message->getFrom()));
        $this->assertEquals('signup@lh.com', key($message->getTo()));
        $this->assertEquals('Добро пожаловать в Lighthouse', $message->getSubject());
        $this->assertContains('Добро пожаловать в Lighthouse!', $message->getBody());
        $this->assertContains('Ваш пароль для входа:', $message->getBody());
        $this->assertContains('Если это письмо пришло вам по ошибке, просто проигнорируйте его', $message->getBody());

        $password = $this->getPasswordFromEmailBody($message->getBody());

        $authClient = $this->factory()->oauth()->getAuthClient();

        $authParams = array(
            'grant_type' => 'password',
            'username' => $userData['email'],
            'password' => $password,
            'client_id' => $authClient->getPublicId(),
            'client_secret' => $authClient->getSecret()
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $authParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $response = $this->client->getResponse()->getContent();
        $jsonResponse = json_decode($response, true);

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);
    }

    public function testGetCurrentAfterSignupAction()
    {
        $userData = array(
            'email' => 'signup@lh.com',
        );

        $postResponse = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $userData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('project.id', $postResponse);
        Assert::assertJsonPathCount(4, 'roles.*', $postResponse);
        Assert::assertJsonPathEquals(User::ROLE_COMMERCIAL_MANAGER, 'roles.*', $postResponse);
        Assert::assertJsonPathEquals(User::ROLE_DEPARTMENT_MANAGER, 'roles.*', $postResponse);
        Assert::assertJsonPathEquals(User::ROLE_STORE_MANAGER, 'roles.*', $postResponse);
        Assert::assertJsonPathEquals(User::ROLE_ADMINISTRATOR, 'roles.*', $postResponse);

        $messages = $this->getSentEmailMessages();
        $this->assertCount(2, $messages, 'There should be two emails logged one from spool and the other one sent');

        $password = $this->getPasswordFromEmailBody($messages[1]->getBody());

        $accessToken = $this->factory()->oauth()->doAuthByUsername('signup@lh.com', $password);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/current'
        );

        $this->assertResponseCode(200);

        $this->assertSame($getResponse, $postResponse);
    }

    public function testPasswordRestoreAction()
    {
        // signup
        $userData = array(
            'email' => 'user@lh.com',
        );

        $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/signup',
            $userData
        );

        $this->assertResponseCode(201);

        $messages = $this->getSentEmailMessages();
        $password = $this->getPasswordFromEmailBody($messages[1]->getBody());

        // restore
        $restoreData = array(
            'email' => 'user@lh.com'
        );

        $restoreResponse = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/restorePassword',
            $restoreData
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('user@lh.com', 'email', $restoreResponse);

        $messages = $this->getSentEmailMessages();
        $this->assertCount(2, $messages);
        $this->assertContains('Вы воспользовались формой восстановления пароля в Lighthouse.', $messages[1]->getBody());
        $this->assertContains('Ваш новый пароль для входа:', $messages[1]->getBody());
        $newPassword = $this->getPasswordFromEmailBody($messages[1]->getBody());

        $this->assertNotEquals($newPassword, $password);
    }

    /**
     * @dataProvider passwordRestoreValidationFailProvider
     * @param string $email
     * @param array $assertions
     */
    public function testPasswordRestoreValidationFail($email, array $assertions)
    {
        $this->factory()->user()->createUser('user@lh.com', 'lighthouse', User::getDefaultRoles());

        $restoreData = array(
            'email' => $email
        );

        $restoreResponse = $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/restorePassword',
            $restoreData
        );

        $this->assertResponseCode(400);
        $this->performJsonAssertions($restoreResponse, $assertions);
    }

    /**
     * @return array
     */
    public function passwordRestoreValidationFailProvider()
    {
        return array(
            'not registered' => array(
                'invalid@lh.com',
                array('children.email.errors.0' => 'Пользователь с таким e-mail не зарегистрирован в системе'),
            ),
            'invalid email' => array(
                'invalid_lh.com',
                array('children.email.errors.0' => 'Пользователь с таким e-mail не зарегистрирован в системе'),
            ),
            'empty' => array(
                '',
                array('children.email.errors.0' => 'Заполните это поле'),
            ),
        );
    }

    public function testUserLoginAfterPasswordRestore()
    {
        $this->factory()->user()->createUser('user@lh.com', 'lighthouse', User::getDefaultRoles());

        $restoreData = array(
            'email' => 'user@lh.com'
        );

        $this->clientJsonRequest(
            null,
            'POST',
            '/api/1/users/restorePassword',
            $restoreData
        );

        $this->assertResponseCode(200);

        $messages = $this->getSentEmailMessages();
        $newPassword = $this->getPasswordFromEmailBody($messages[1]->getBody());

        $this->factory()->clear();

        try {
            $this->factory()->oauth()->doAuthByUsername('user@lh.com', 'lighthouse');
            $this->fail('Old password should not fit');
        } catch (OAuth2ServerException $e) {
            $this->assertTrue(true);
        }

        $accessToken = $this->factory()->oauth()->doAuthByUsername('user@lh.com', $newPassword);
        $this->assertNotNull($accessToken->access_token);
    }

    /**
     * @return \Swift_Message[]
     */
    protected function getSentEmailMessages()
    {
        /* @var \Swift_Plugins_MessageLogger $messageLogger */
        return $this->getContainer()->get('swiftmailer.plugin.messagelogger')->getMessages();
    }

    /**
     * @param string $body
     * @return string
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    protected function getPasswordFromEmailBody($body)
    {
        if (preg_match('/пароль для входа:\s*(.+?)\n/u', $body, $matches)) {
            return $matches[1];
        }
        throw new \PHPUnit_Framework_AssertionFailedError('Password not found in message');
    }
}
