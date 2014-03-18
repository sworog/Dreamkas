<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class DepartmentControllerTest extends WebTestCase
{
    public function testPostDepartmentsAction()
    {
        $storeId = $this->factory->store()->getStoreId('магазин_номер_42');

        $departmentData = array(
            'number' => '42',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('42', 'number', $postResponse);
        Assert::assertJsonPathEquals('Винно-водочные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals($storeId, 'store.id', $postResponse);
        Assert::assertJsonPathEquals('магазин_номер_42', 'store.number', $postResponse);
    }

    public function testUniqueDepartmentNumber()
    {
        $storeId1 = $this->factory->store()->getStoreId('Алкоголь');
        $storeId2 = $this->factory->store()->getStoreId('магазин_номер_42');

        $departmentData = array(
            'number' => 'номер_1',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId1,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        // Create first department
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second department with same name in store 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData
        );
        $this->assertResponseCode(400);

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

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second department with same name in store 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $departmentData2
        );
        $this->assertResponseCode(400);

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
        $storeId = $this->factory->store()->getStoreId('магазин_номер_42');

        $categoryData = $data + array(
                'number' => 'номер-1',
                'name' => 'Винно-водочные изделия',
                'store' => $storeId,
            );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $categoryData
        );

        $this->assertResponseCode($expectedCode);

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
        $storeId = $this->factory->store()->getStoreId('магазин_номер_42');

        $departmentId = $this->createDepartment($storeId);

        $putData = $data + array(
            'number' => 'номер-1',
            'name' => 'Винно-водочные изделия',
            'store' => $storeId,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/departments/' . $departmentId,
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetDepartment()
    {
        $storeId = $this->factory->store()->getStoreId();
        $departmentId = $this->createDepartment($storeId);

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/departments/' . $departmentId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($departmentId, 'id', $getResponse);
        Assert::assertJsonPathEquals($storeId, 'store.id', $getResponse);
    }

    public function testGetDepartmentNotFound()
    {
        $storeId1 = $this->factory->store()->getStoreId('1');
        $this->createDepartment($storeId1, '1-1');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/departments/invalidId'
        );

        $this->assertResponseCode(404);
    }

    public function testGetDepartments()
    {
        $storeId1 = $this->factory->store()->getStoreId('1');
        $storeId2 = $this->factory->store()->getStoreId('2');

        $departmentId1 = $this->createDepartment($storeId1, '1-1');
        $departmentId2 = $this->createDepartment($storeId1, '1-2');
        $departmentId3 = $this->createDepartment($storeId1, '1-3');

        $departmentId4 = $this->createDepartment($storeId2, '2-4');
        $departmentId5 = $this->createDepartment($storeId2, '2-5');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/departments'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($departmentId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId3, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($departmentId4, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($departmentId5, '*.id', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/departments'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($departmentId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($departmentId5, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($departmentId1, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($departmentId2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($departmentId3, '*.id', $getResponse);
    }

    public function testGetDepartmentsNotFound()
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/123484923423/departments'
        );

        $this->assertResponseCode(404);
    }

    public function testGetDepartmentsEmptyCollection()
    {
        $storeId = $this->factory->store()->getStoreId();

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/departments'
        );

        $this->assertResponseCode(200);

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
        $storeId = $this->factory->store()->getStoreId();
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
        $accessToken = $this->factory->oauth()->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array(
                    'number' => 'отдел-33',
                    'name' => 'Пиво',
                    'store' => $storeId,
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
                '/api/1/stores/__STORE_ID__/departments',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/stores/__STORE_ID__/departments',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores/__STORE_ID__/departments',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/stores/__STORE_ID__/departments',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }
}
