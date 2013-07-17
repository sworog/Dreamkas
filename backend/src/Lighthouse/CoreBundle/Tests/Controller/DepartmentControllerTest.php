<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class DepartmentControllerTest extends WebTestCase
{
    public function testPostDepartmentsAction()
    {
        $this->clearMongoDb();

        $storeId = $this->createStore('магазин_номер_42');

        $departmentData = array(
            'number' => '42',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('42', 'number', $postResponse);
        Assert::assertJsonPathEquals('Винно-водочные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals($storeId, 'store.id', $postResponse);
        Assert::assertJsonPathEquals('магазин_номер_42', 'store.number', $postResponse);
    }

    public function testUniqueDepartmentNumber()
    {
        $this->clearMongoDb();

        $storeId1 = $this->createStore('Алкоголь');
        $storeId2 = $this->createStore('магазин_номер_42');

        $departmentData = array(
            'number' => 'номер_1',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId1,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        // Create first department
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second department with same name in store 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Отдел с таким названием уже существует в этом магазине',
            'children.number.errors',
            $postResponse
        );

        $departmentData2 = array('store' => $storeId2) + $departmentData;

        // Create department with same name but in store 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData2
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second department with same name in store 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData2
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Отдел с таким названием уже существует в этом магазине',
            'children.number.errors',
            $postResponse
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationDepartmentProvider
     */
    public function testPostDepartmentsValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $storeId = $this->createStore('магазин_номер_42');

        $categoryData = $data + array(
                'number' => 'номер-1',
                'name' => 'Винно-водочные изделия',
                'store' => $storeId,
            );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $categoryData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationDepartmentProvider()
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
            'not valid store' => array(
                400,
                array('store' => '1234'),
                array(
                    'children.store.errors.0'
                    =>
                    'Такого магазина не существует'
                )
            ),
            'valid long 100 name' => array(
                201,
                array('name' => str_repeat('z', 100)),
            ),

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
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationDepartmentProvider
     */
    public function testPutDepartmentsValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $storeId = $this->createStore('магазин_номер_42');

        $departmentId = $this->createDepartment($storeId);

        $putData = $data + array(
            'number' => 'номер-1',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/departments/' . $departmentId,
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetDepartment()
    {
        $this->clearMongoDb();

        $storeId = $this->createStore();
        $departmentId = $this->createDepartment($storeId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/departments/' . $departmentId
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($departmentId, 'id', $getResponse);
        Assert::assertJsonPathEquals($storeId, 'store.id', $getResponse);
    }

    public function testGetDepartmentNotFound()
    {
        $this->clearMongoDb();

        $storeId1 = $this->createStore('1');
        $this->createDepartment($storeId1, '1.1');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/departments/invalidId'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetDepartments()
    {
        $this->clearMongoDb();

        $storeId1 = $this->createStore('1');
        $storeId2 = $this->createStore('2');

        $departmentId1 = $this->createDepartment($storeId1, '1.1');
        $departmentId2 = $this->createDepartment($storeId1, '1.2');
        $departmentId3 = $this->createDepartment($storeId1, '1.3');

        $departmentId4 = $this->createDepartment($storeId2, '2.4');
        $departmentId5 = $this->createDepartment($storeId2, '2.5');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/departments'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($departmentId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($departmentId5, '*.id', $getResponse, false);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/departments'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($departmentId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($departmentId2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($departmentId3, '*.id', $getResponse, false);
    }

    public function testGetDepartmentsNotFound()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/123484923423/departments'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetDepartmentsEmptyCollection()
    {
        $this->clearMongoDb();

        $storeId = $this->createStore();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/departments'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array|null $requestData
     *
     * @dataProvider accessCategoryProvider
     */
    public function testAccessDepartments($url, $method, $role, $responseCode, $requestData = null)
    {
        $this->clearMongoDb();

        $storeId = $this->createStore();
        $departmentId = $this->createDepartment($storeId);

        $url = str_replace(
            array(
                '__DEPARTMENT_ID__',
                '__STORE_ID__'
            ),
            array(
                $departmentId,
                $storeId,
            ),
            $url
        );
        $accessToken = $this->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array(
                    'number' => 'отдел-33',
                    'name' => 'Пиво',
                    'store' => $storeId,
                );
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        Assert::assertResponseCode($responseCode, $this->client);
    }

    public function accessCategoryProvider()
    {
        return array(
            /*************************************
             * GET /api/1/departments/__ID__
             */
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/departments
             */
            array(
                '/api/1/departments',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/departments',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/departments',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/departments',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/departments/__DEPARTMENT_ID__
             */
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/departments/__DEPARTMENT_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * GET /api/1/groups/__GROUP_ID__/departments
             */
            array(
                '/api/1/groups/__GROUP_ID__/departments',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/departments',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/departments',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/departments',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }
}
