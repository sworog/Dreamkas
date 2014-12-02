<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GroupControllerTest extends WebTestCase
{
    public function testPostGroupAction()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1'
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('Продовольственные товары', 'name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
    }

    public function testPostGroupActionWithoutRounding()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('Продовольственные товары', 'name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonHasPath('rounding.name', $postResponse);
    }

    public function testPutGroupActionRoundingUpdated()
    {
        $group = $this->factory()->catalog()->getGroup('Алкоголь');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $getResponse);

        $groupData = array(
            'name' => 'Алкоголь',
            'rounding' => 'nearest50',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/groups/{$group->id}",
            $groupData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', '0.rounding.name', $getResponse);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPostGroupValidation($expectedCode, array $data, array $assertions = array())
    {
        $groupData = $data + array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationGroupProvider()
    {
        return array(
            'not valid empty name' => array(
                400,
                array('name' => ''),
                array(
                    'errors.children.name.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 101 name' => array(
                400,
                array('name' => str_repeat('z', 101)),
                array(
                    'errors.children.name.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'valid long 100 name' => array(
                201,
                array('name' => str_repeat('z', 100)),
            ),
            // retail markup
            'valid markup' => array(
                201,
                array('retailMarkupMin' => 10.01, 'retailMarkupMax' => 20.13),
            ),
            'valid markup lower boundary' => array(
                201,
                array('retailMarkupMin' => 0, 'retailMarkupMax' => 1000),
            ),
            'valid markup min equals max' => array(
                201,
                array('retailMarkupMin' => 10.12, 'retailMarkupMax' => 10.12),
            ),
            'not valid markup -0.01' => array(
                400,
                array('retailMarkupMin' => -0.01, 'retailMarkupMax' => 100),
                array('errors.children.retailMarkupMin.errors.0' => 'Значение должно быть больше или равно 0')
            ),
            'not valid markup min is more than max' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 0),
                array(
                    'errors.children.retailMarkupMin.errors.0'
                    =>
                    'Минимальная наценка не может быть больше максимальной'
                )
            ),
            'not valid markup not float' => array(
                400,
                array('retailMarkupMin' => 'aaa', 'retailMarkupMax' => 'bbb'),
                array('errors.children.retailMarkupMin.errors.*' => 'Значение должно быть числом'),
                array('errors.children.retailMarkupMax.errors.*' => 'Значение должно быть числом')
            ),
            'not valid markup min not float' => array(
                400,
                array('retailMarkupMin' => 'aaa', 'retailMarkupMax' => 10),
                array('errors.children.retailMarkupMin.errors.*' => 'Значение должно быть числом'),
            ),
            'not valid markup max not float' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 'bbb'),
                array('errors.children.retailMarkupMax.errors.*' => 'Значение должно быть числом'),
            ),
            // rounding
            'valid rounding nearest1' => array(
                201,
                array('rounding' => 'nearest1'),
                array('rounding.name' => 'nearest1', 'rounding.title' => 'до копеек')
            ),
            'valid rounding nearest10' => array(
                201,
                array('rounding' => 'nearest10'),
                array('rounding.name' => 'nearest10', 'rounding.title' => 'до 10 копеек')
            ),
            'valid rounding nearest50' => array(
                201,
                array('rounding' => 'nearest50'),
                array('rounding.name' => 'nearest50', 'rounding.title' => 'до 50 копеек')
            ),
            'valid rounding nearest100' => array(
                201,
                array('rounding' => 'nearest100'),
                array('rounding.name' => 'nearest100', 'rounding.title' => 'до рублей')
            ),
            'valid rounding nearest99' => array(
                201,
                array('rounding' => 'nearest99'),
                array('rounding.name' => 'nearest99', 'rounding.title' => 'до 99 копеек')
            ),
            'invalid rounding aaaa' => array(
                400,
                array('rounding' => 'aaaa'),
                array(
                    'errors.children.rounding.errors.0' => 'Значение недопустимо.',
                )
            ),
            'valid rounding no value, should be default rounding' => array(
                201,
                array('rounding' => null),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'valid rounding empty value, should be default rounding' => array(
                201,
                array('rounding' => ''),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
        );
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPutGroupValidation($expectedCode, array $data, array $assertions = array())
    {
        $group = $this->factory()->catalog()->getGroup('Прод тов');

        $groupData = $data + array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/groups/{$group->id}",
            $groupData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function testGetGroup()
    {
        $group = $this->factory()->catalog()->getGroup('Прод Тов');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}"
        );

        Assert::assertJsonPathEquals($group->id, 'id', $postResponse);
        Assert::assertJsonPathEquals('Прод Тов', 'name', $postResponse);
        Assert::assertJsonHasPath('rounding.name', $postResponse);
        Assert::assertJsonHasPath('rounding.title', $postResponse);
    }

    public function testGetGroupWithCategoriesAndSubCategories()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => array(),
                        '1.1.2' => array(),
                        '1.1.3' => array(),
                    ),
                    '1.2' => array(
                        '1.2.1' => array(),
                        '1.2.2' => array(),
                        '1.2.3' => array(),
                        '1.2.4' => array(),
                    )
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$catalog['1']->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('id', $getResponse);
        Assert::assertJsonHasPath('categories', $getResponse);

        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1.1']->id, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.2']->id, 'categories.*.id', $getResponse, 1);

        Assert::assertJsonPathCount(true, 'categories.0.subCategories.*.id', $getResponse);
        Assert::assertJsonPathCount(true, 'categories.1.subCategories.*.id', $getResponse);
    }

    public function testGetGroups()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                'Прод Тов 1' => array(),
                'Прод Тов 2' => array(),
                'Прод Тов 3' => array(),
                'Прод Тов 4' => array(),
                'Прод Тов 5' => array(),
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $postResponse);

        foreach ($catalog as $group) {
            Assert::assertJsonPathEquals($group->id, '*.id', $postResponse, 1);
        }
    }

    public function testGroupUnique()
    {
        $this->factory()->catalog()->getGroup('Прод Тов');

        $postData = array(
            'name' => 'Прод Тов',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $postData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Такая группа уже есть', 'errors.children.name.errors.0', $postResponse);
    }

    public function testDeleteGroupNoCategories()
    {
        $group = $this->factory()->catalog()->getGroup();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/groups/{$group->id}"
        );

        $this->assertResponseCode(204);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}"
        );

        $this->assertResponseCode(404);
    }

    public function testDeleteGroupWithCategories()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '0' => array(
                    '1' => array(),
                    '2' => array(),
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/groups/{$catalog[0]->id}"
        );

        $this->assertResponseCode(409);
        Assert::assertJsonHasPath('message', $response);
    }

    public function testGroupWithCategories()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '0' => array(
                    '1' => array(),
                    '2' => array()
                )
            )
        );

        $this->client->shutdownKernelBeforeRequest();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$catalog['0']->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1']->id, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2']->id, 'categories.*.id', $getResponse, 1);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array     $requestData
     *
     * @dataProvider accessGroupProvider
     */
    public function testAccessGroup($url, $method, $role, $responseCode, array $requestData = array())
    {
        $group = $this->factory()->catalog()->getGroup();

        $url = str_replace('__GROUP_ID__', $group->id, $url);

        $accessToken = $this->factory()->oauth()->authAsRole($role);

        $requestData += array(
            'name' => 'Алкоголь',
            'rounding' => 'nearest1',
        );

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        $this->assertResponseCode($responseCode);
    }

    public function accessGroupProvider()
    {
        return array(
            /**************************************
             * GET /api/1/groups
             */
            array(
                '/api/1/groups',
                'GET',                              // Method
                User::ROLE_COMMERCIAL_MANAGER,          // Role
                200,                              // Response Code
            ),
            array(
                '/api/1/groups',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * GET /api/1/groups/__ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * POST /api/1/groups
             */
            array(
                '/api/1/groups',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            array(
                '/api/1/groups',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * PUT /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * DELETE /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                User::ROLE_COMMERCIAL_MANAGER,
                204,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
        );
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerHasStore($role, $rel)
    {
        $manager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $group = $this->factory()->catalog()->getGroup();
        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $manager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($manager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/groups/{$group->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($group->id, 'id', $getResponse);
        Assert::assertJsonHasPath('rounding.name', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerFromAnotherStore($role, $rel)
    {
        $manager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $group = $this->factory()->catalog()->getGroup();
        $store1 = $this->factory()->store()->getStore('42');
        $store2 = $this->factory()->store()->getStore('43');

        $this->factory()->store()->linkManagers($store1->id, $manager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($manager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/groups/{$group->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerHasNoStore($role)
    {
        $manager = $this->factory()->user()->getRoleUser($role);

        $group = $this->factory()->catalog()->getGroup();
        $store = $this->factory()->store()->getStore();

        $accessToken = $this->factory()->oauth()->auth($manager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/groups/{$group->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupsStoreManagerHasStore($role, $rel)
    {
        $manager = $this->factory()->user()->getRoleUser($role);

        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(),
                '2' => array(),
                '3' => array()
            )
        );

        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $manager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($manager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/groups"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        foreach ($catalog as $group) {
            Assert::assertJsonPathEquals($group->id, '*.id', $getResponse, 1);
        }
    }

    /**
     * @return array
     */
    public function storeRolesProvider()
    {
        return array(
            'store manager' => array(User::ROLE_STORE_MANAGER, Store::REL_STORE_MANAGERS),
            'department manager' => array(User::ROLE_DEPARTMENT_MANAGER, Store::REL_DEPARTMENT_MANAGERS),
        );
    }

    /**
     * @group unique
     */
    public function testUniqueNameInParallel()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1'
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/groups', 'POST', $groupData);
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
        Assert::assertJsonPathEquals('Продовольственные товары', '*.name', $jsonResponses, 1);
        Assert::assertJsonPathEquals('Такая группа уже есть', '*.errors.children.name.errors.0', $jsonResponses, 2);
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1'
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $group = new Group();

        $documentManagerMock = $this->getMockBuilder('Doctrine\\ODM\\MongoDB\\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $documentManagerMock
            ->expects($this->once())
            ->method('persist');

        $documentManagerMock
            ->expects($this->once())
            ->method('flush')
            ->with($this->isEmpty())
            ->will($this->throwException($exception));

        $groupRepositoryMock = $this->getMockBuilder(
            'Lighthouse\\CoreBundle\\Document\\Classifier\\Group\\GroupRepository'
        )->disableOriginalConstructor()->getMock();

        $groupRepositoryMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($group));

        $groupRepositoryMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($groupRepositoryMock) {
                $container->set('lighthouse.core.document.repository.classifier.group', $groupRepositoryMock);
            }
        );

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        return $response;
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedException()
    {
        $exception = new \Exception('Unknown exception');
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(500);
        Assert::assertJsonPathEquals('Unknown exception', 'message', $response);
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedMongoDuplicateKeyException()
    {
        $exception = new \MongoDuplicateKeyException();
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Validation Failed', 'message', $response);
        Assert::assertJsonPathEquals('Такая группа уже есть', 'errors.children.name.errors.0', $response);
    }
}
