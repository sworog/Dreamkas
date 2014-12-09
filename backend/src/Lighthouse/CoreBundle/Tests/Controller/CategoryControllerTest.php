<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Exception;
use MongoDuplicateKeyException;

class CategoryControllerTest extends WebTestCase
{
    public function testPostCategoriesAction()
    {
        $group = $this->factory()->catalog()->getGroup('Продовольственные товары');

        $categoryData = array(
            'name' => 'Винно-водочные изделия',
            'group' => $group->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('Винно-водочные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals($group->id, 'group.id', $postResponse);
        Assert::assertJsonPathEquals('Продовольственные товары', 'group.name', $postResponse);
    }

    public function testUniqueCategoryName()
    {
        $group1 = $this->factory()->catalog()->getGroup('Алкоголь');
        $group2 = $this->factory()->catalog()->getGroup('Продовольственные товары');

        $categoryData = array(
            'name' => 'Винно-водочные изделия',
            'group' => $group1->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        // Create first category
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second category with same name in group 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Категория с таким названием уже существует в этой группе',
            'errors.children.name.errors',
            $postResponse
        );

        $categoryData2 = array('group' => $group2->id) + $categoryData;

        // Create category with same name but in group 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData2
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second category with same name in group 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData2
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Категория с таким названием уже существует в этой группе',
            'errors.children.name.errors',
            $postResponse
        );
    }

    public function testPutCategoryActionRoundingUpdated()
    {
        $group = $this->factory()->catalog()->createGroup('Алкоголь');
        $category = $this->factory()->catalog()->createCategory($group->id, 'Водка');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $getResponse);

        $categoryData = array(
            'name' => 'Водка',
            'group' => $group->id,
            'rounding' => 'nearest50',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/categories/{$category->id}",
            $categoryData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', '0.rounding.name', $getResponse);
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationCategoryProvider
     */
    public function testPostCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $group = $this->factory()->catalog()->getGroup('Продовольственные товары');

        $categoryData = $data + array(
            'name' => 'Винно-водочные изделия',
            'group' => $group->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationCategoryProvider()
    {
        return array(
            'not valid empty name' => array(
                400,
                array('name' => ''),
                array('errors.children.name.errors.0' => 'Заполните это поле')
            ),
            'not valid long 101 name' => array(
                400,
                array('name' => str_repeat('z', 101)),
                array('errors.children.name.errors.0' => 'Не более 100 символов')
            ),
            'not valid group' => array(
                400,
                array('group' => '1234'),
                array('errors.children.group.errors.0' => 'Такой группы не существует')
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
                array('errors.children.rounding.errors.0' => 'Значение недопустимо.',)
            ),
            'valid rounding no value, should inherit group value' => array(
                201,
                array('rounding' => null),
                array('rounding.name' => 'nearest1',)
            ),
            'valid rounding empty value, should inherit group value' => array(
                201,
                array('rounding' => ''),
                array('rounding.name' => 'nearest1',)
            ),
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationCategoryProvider
     */
    public function testPutCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $group = $this->factory()->catalog()->getGroup('Продовольственные товары');
        $category = $this->factory()->catalog()->createCategory($group->id);

        $putData = $data + array(
            'name' => 'Винно-водочные изделия',
            'group' => $group->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/categories/{$category->id}",
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetCategory()
    {
        $group = $this->factory()->catalog()->getGroup();
        $category = $this->factory()->catalog()->createCategory($group->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($category->id, 'id', $getResponse);
        Assert::assertJsonPathEquals($group->id, 'group.id', $getResponse);
    }

    public function testGetCategoryNotFound()
    {
        $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array()
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/invalidId'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategoryWithSubcategories()
    {
        $category = $this->factory()->catalog()->getCategory();
        $this->factory()->catalog()->createSubCategory($category->id, '1');
        $this->factory()->catalog()->createSubCategory($category->id, '2');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('id', $getResponse);
        Assert::assertJsonHasPath('subCategories', $getResponse);
        Assert::assertJsonPathCount(2, 'subCategories.*.id', $getResponse);
        Assert::assertJsonPathEquals('1', 'subCategories.*.name', $getResponse);
        Assert::assertJsonPathEquals('2', 'subCategories.*.name', $getResponse);
    }

    public function testGetCategories()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(),
                    '1.2' => array(),
                    '1.3' => array(),
                ),
                '2' => array(
                    '2.4' => array(),
                    '2.5' => array(),
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$catalog['1']->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.3']->id, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($catalog['2.4']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['2.5']->id, '*.id', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$catalog['2']->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['2.4']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2.5']->id, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($catalog['1.1']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['1.2']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['1.3']->id, '*.id', $getResponse);
    }

    public function testGetCategoriesNotFound()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/123484923423/categories'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategoriesEmptyCollection()
    {
        $group = $this->factory()->catalog()->getGroup();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/groups/{$group->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testDeleteCategory()
    {
        $group = $this->factory()->catalog()->getGroup();
        $category = $this->factory()->catalog()->getCategory($group->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(204);
    }

    public function testDeleteCategoryWithSubcategories()
    {
        $category = $this->factory()->catalog()->getCategory();

        $this->factory()->catalog()->createSubCategory($category->id, '1');
        $this->factory()->catalog()->createSubCategory($category->id, '2');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(409);

        $request = new JsonRequest("/api/1/categories/{$category->id}", 'DELETE');
        $request->setAccessToken($accessToken);
        $request->addHttpHeader('Accept', 'application/json, text/javascript, */*; q=0.01');

        $this->client->setCatchException();
        $response = $this->client->jsonRequest($request);

        $this->assertResponseCode(409);
        Assert::assertJsonHasPath('message', $response);
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
    public function testAccessCategory($url, $method, $role, $responseCode, array $requestData = array())
    {
        $group = $this->factory()->catalog()->getGroup();
        $category = $this->factory()->catalog()->createCategory($group->id);

        $url = str_replace(
            array(
                '__CATEGORY_ID__',
                '__GROUP_ID__'
            ),
            array(
                $category->id,
                $group->id,
            ),
            $url
        );

        $accessToken = $this->factory()->oauth()->authAsRole($role);

        $requestData += array(
            'name' => 'Пиво',
            'group' => $group->id,
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

    /**
     * @return array
     */
    public function accessCategoryProvider()
    {
        return array(
            /*************************************
             * GET /api/1/categories/__ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * POST /api/1/categories
             */
            array(
                '/api/1/categories',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            array(
                '/api/1/categories',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * PUT /api/1/categories/__CATEGORY_ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * DELETE /api/1/categories/__CATEGORY_ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                User::ROLE_COMMERCIAL_MANAGER,
                204,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * GET /api/1/groups/__GROUP_ID__/categories
             */
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
        );
    }

    public function testRetailMarkupIsNullOnCategoryCreateWithEmptyMarkup()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);
    }

    public function testRetailMarkupIsNotInheritedFromGroupAfterGroupUpdate()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/groups/' . $groupId,
            array(
                'name' => 'Алкоголь',
                'rounding' => 'nearest1',
                'retailMarkupMin' => 15,
                'retailMarkupMax' => 25,
            )
        );

        $this->assertResponseCode(200);

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);
    }

    public function testRetailMarkupBecomesNullIfNullMarkupPassed()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Сухое вино',
            'group' => $groupId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $categoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Сладкое вино',
            'group' => $groupId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => null,
            'retailMarkupMax' => null,
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/categories/' . $categoryId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $putResponse);
    }

    public function testRetailMarkupBecomesNullIfNoMarkupPassed()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $this->client->shutdownKernelBeforeRequest();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'group' => $groupId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'group' => $groupId,
            'rounding' => 'nearest1',
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/categories/' . $subCategoryId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $putResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreCategoryStoreManagerHasStore($role, $rel)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $category = $this->factory()->catalog()->getCategory();
        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $storeManager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/categories/{$category->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($category->id, 'id', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreCategoryStoreManagerFromAnotherStore($role, $rel)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $category = $this->factory()->catalog()->getCategory();
        $store1 = $this->factory()->store()->getStore('42');
        $store2 = $this->factory()->store()->getStore('43');

        $this->factory()->store()->linkManagers($store1->id, $storeManager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/categories/{$category->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreCategoryStoreManagerHasNoStore($role)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $category = $this->factory()->catalog()->getCategory();
        $store = $this->factory()->store()->getStore();

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/categories/{$category->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupCategoriesStoreManagerHasStore($role, $rel)
    {
        $manager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $manager->id, $rel);

        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(),
                    '1.2' => array(),
                    '1.3' => array(),
                    '1.4' => array(),
                ),
                '2' => array(
                    '2.1' => array(),
                    '2.2' => array(),
                )
            )
        );

        $accessToken = $this->factory()->oauth()->auth($manager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/groups/{$catalog['1']->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(4, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.3']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.4']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1']->id, '*.group.id', $getResponse, 4);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/groups/{$catalog['2']->id}/categories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['2.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2']->id, '*.group.id', $getResponse, 2);
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

    public function testRoundingIsInheritedFromGroup()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', 'nearest50')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Водка',
            'group' => $groupId,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        $categoryId = $postResponse['id'];

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId,
            $postData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);
        Assert::assertJsonPathEquals('nearest50', 'group.rounding.name', $getResponse);
    }

    /**
     * @group unique
     */
    public function testUniqueNameInParallel()
    {
        $group = $this->factory()->catalog()->getGroup();
        $categoryData = array(
            'name' => 'Молочка',
            'rounding' => 'nearest1',
            'group' => $group->id
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/categories', 'POST', $categoryData);
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
        Assert::assertJsonPathEquals('Молочка', '*.name', $jsonResponses, 1);
        Assert::assertJsonPathEquals(
            'Категория с таким названием уже существует в этой группе',
            '*.errors.children.name.errors.0',
            $jsonResponses,
            2
        );
    }

    /**
     * @param Exception $exception
     * @return array
     */
    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $group = $this->factory()->catalog()->getGroup();
        $categoryData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
            'group' => $group->id
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $category = new Category();

        $documentManagerMock = $this
            ->getMockBuilder('Doctrine\\ODM\\MongoDB\\DocumentManager')
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

        $categoryRepositoryMock = $this
            ->getMockBuilder('Lighthouse\\CoreBundle\\Document\\Classifier\\Category\\CategoryRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $categoryRepositoryMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($category));
        $categoryRepositoryMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($categoryRepositoryMock) {
                $container->set('lighthouse.core.document.repository.classifier.category', $categoryRepositoryMock);
            }
        );

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
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
            'Категория с таким названием уже существует в этой группе',
            'errors.children.name.errors.0',
            $response
        );
    }
}
