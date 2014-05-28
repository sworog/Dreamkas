<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Test\Assert;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Exception;
use MongoDuplicateKeyException;

class StoreControllerTest extends WebTestCase
{
    public function testPostStoreAction()
    {
        $storeData = array(
            'number' => 'магазин_номер-32',
            'address' => 'СПБ, профессора Попова пр., д. 37, пом 3А',
            'contacts' => 'тел. 344-32-54, тел/факс +7-921-334-2343, email:super@store.spb.ru',
        );

        $accessToken = $this->factory()->oauth()->authAsRole("ROLE_COMMERCIAL_MANAGER");

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        foreach ($storeData as $name => $value) {
            Assert::assertJsonPathEquals($value, $name, $response);
        }
    }

    public function testStoreUnique()
    {
        $this->factory()->store()->getStoreId("42");

        $storeData = array(
            'number' => '42',
            'address' => 'qwe',
            'contacts' => 'qwew',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Такой магазин уже есть', 'children.number.errors.0', $response);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationStoreProvider
     */
    public function testPostStoreValidation($expectedCode, array $data, array $assertions = array())
    {
        $storeData = $data + array(
            'number' => 'магазин_номер-32',
            'address' => 'СПБ, профессора Попова пр., д. 37, пом 3А',
            'contacts' => 'тел. 344-32-54, тел/факс +7-921-334-2343, email:super@store.spb.ru',
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationStoreProvider
     */
    public function testPutStoreValidation($expectedCode, array $data, array $assertions = array())
    {
        $storeId = $this->factory()->store()->getStoreId();

        $storeData = $data + array(
                'number' => 'магазин_номер-32',
                'address' => 'СПБ, профессора Попова пр., д. 37, пом 3А',
                'contacts' => 'тел. 344-32-54, тел/факс +7-921-334-2343, email:super@store.spb.ru',
            );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId,
            $storeData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    public function validationStoreProvider()
    {
        return array(
            /****************************************
             * number
             */
            'not valid empty number' => array(
                400,
                array('number' => ''),
                array(
                    'children.number.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 51 number' => array(
                400,
                array('number' => str_repeat('z', 51)),
                array(
                    'children.number.errors.0'
                    =>
                    'Не более 50 символов'
                )
            ),
            'valid long 50 number' => array(
                201,
                array('number' => str_repeat('z', 50)),
            ),
            'valid symbols number' => array(
                201,
                array('number' => 'QWEqweФЫВфыв123-_-34'),
            ),
            'not valid symbols number' => array(
                400,
                array('number' => 'ASdqwe123466!@#$%^&*(;"'),
                array(
                    'children.number.errors.0'
                    =>
                    'Значение недопустимо'
                )
            ),

            /****************************************
             * address
             */
            'valid long 300 address' => array(
                201,
                array('address' => str_repeat('z', 300)),
            ),
            'not valid long 301 address' => array(
                400,
                array('address' => str_repeat('z', 301)),
                array(
                    'children.address.errors.0'
                    =>
                    'Не более 300 символов'
                ),
            ),
            'valid symbols address' => array(
                201,
                array('address' => 'ASdqwe123466!@#$%^&*(;"'),
            ),
            'not valid empty address' => array(
                400,
                array('address' => ''),
                array(
                    'children.address.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),

            /****************************************
             * contacts
             */
            'valid long 100 contacts' => array(
                201,
                array('contacts' => str_repeat('z', 100)),
            ),
            'not valid long 101 contacts' => array(
                400,
                array('contacts' => str_repeat('z', 101)),
                array(
                    'children.contacts.errors.0'
                    =>
                    'Не более 100 символов'
                ),
            ),
            'valid symbols contacts' => array(
                201,
                array('contacts' => 'ASdqwe123466!@#$%^&*(;"'),
            ),
            'not valid empty contacts' => array(
                400,
                array('contacts' => ''),
                array(
                    'children.contacts.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
        );
    }

    public function testGetStores()
    {
        $storesIds = $this->factory()->store()->getStores(array(0, 1, 2, 3, 4));

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $response);

        foreach ($storesIds as $id) {
            Assert::assertJsonPathEquals($id, '*.id', $response);
        }
    }

    public function testGetStoreAction()
    {
        $storeData = array(
            'number' => 'магазин_номер-32',
            'address' => 'СПБ, профессора Попова пр., д. 37, пом 3А',
            'contacts' => 'тел. 344-32-54, тел/факс +7-921-334-2343, email:super@store.spb.ru',
        );

        $accessToken = $this->factory()->oauth()->authAsRole("ROLE_COMMERCIAL_MANAGER");

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        foreach ($storeData as $name => $value) {
            Assert::assertJsonPathEquals($value, $name, $response);
        }

        $id = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $id
        );

        $this->assertResponseCode(200);

        foreach ($storeData as $name => $value) {
            Assert::assertJsonPathEquals($value, $name, $response);
        }
    }

    public function testRolesPropertyIsNotExposed()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId
        );

        $this->assertResponseCode(200);
        Assert::assertNotJsonHasPath('roles', $getResponse);
    }

    public function testGetStoreWithDepartments()
    {
        $store = $this->factory()->store()->getStore('1');

        $departmentId1 = $this->factory()->store()->getDepartment('1-1', $store)->id;
        $departmentId2 = $this->factory()->store()->getDepartment('1-2', $store)->id;
        $this->client->shutdownKernelBeforeRequest();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('id', $getResponse);
        Assert::assertJsonHasPath('departments', $getResponse);

        Assert::assertJsonPathCount(2, 'departments.*.id', $getResponse);
        Assert::assertJsonPathEquals($departmentId1, 'departments.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId2, 'departments.*.id', $getResponse, 1);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array|null $requestData
     *
     * @dataProvider accessStoreProvider
     */
    public function testAccessStore($url, $method, $role, $responseCode, $requestData = null)
    {
        $storeId = $this->factory()->store()->getStoreId();

        $url = str_replace(
            array(
                '__STORE_ID__',
            ),
            array(
                $storeId,
            ),
            $url
        );
        $accessToken = $this->factory()->oauth()->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array (
                'number' => 'магазин_номер-32',
                'address' => 'СПБ, профессора Попова пр., д. 37, пом 3А',
                'contacts' => 'тел. 344-32-54, тел/факс +7-921-334-2343, email:super@store.spb.ru',
            );
        }

        $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        $this->assertResponseCode($responseCode);
    }

    public function accessStoreProvider()
    {
        return array(
            /**************************************
             * GET /api/1/stores
             */
            array(
                '/api/1/stores',
                'GET',                              // Method
                'ROLE_COMMERCIAL_MANAGER',          // Role
                '200',                              // Response Code
            ),
            array(
                '/api/1/stores',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * GET /api/1/stores/__ID__
             */
            array(
                '/api/1/stores/__STORE_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/stores
             */
            array(
                '/api/1/stores',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/stores',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/stores',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/stores',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/stores/__STORE_ID__
             */
            array(
                '/api/1/stores/__STORE_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/stores/__STORE_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),
        );
    }

    /**
     * @group unique
     */
    public function testUniqueUsernameInParallel()
    {
        $storeData = array(
            'number' => '32',
            'address' => 'СПБ, Профессора Попова пр., д. 37, пом 3А',
            'contacts' => '344-32-54',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/stores', 'POST', $storeData);
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
        Assert::assertJsonPathEquals('32', '*.number', $jsonResponses, 1);
        Assert::assertJsonPathEquals('Такой магазин уже есть', '*.children.number.errors.0', $jsonResponses, 2);
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $storeData = array(
            'number' => '32',
            'address' => 'СПБ, Профессора Попова пр., д. 37, пом 3А',
            'contacts' => '344-32-54',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $store = new Store();

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

        $storeRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Store\\StoreRepository',
            array(),
            array(),
            '',
            false
        );

        $storeRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($store));
        $storeRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($storeRepoMock) {
                $container->set('lighthouse.core.document.repository.store', $storeRepoMock);
            }
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
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
            'Такой магазин уже есть',
            'children.number.errors.0',
            $response
        );
    }

    /**
     * @group unique
     * @expectedException MongoDuplicateKeyException
     */
    public function testDoubleCreate()
    {
        $this->factory()->store()->createStore();
        $this->factory()->flush();
        $this->factory()->store()->createStore();
        $this->factory()->flush();
    }
}
