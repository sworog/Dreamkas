<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use MongoDuplicateKeyException;
use Exception;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubCategoryControllerTest extends WebTestCase
{
    public function testPostSubCategoriesAction()
    {
        $group = $this->factory()->catalog()->getGroup('Алкоголь');
        $category = $this->factory()->catalog()->createCategory($group->id, 'Крепкий алкоголь');

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $category->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('Водка', 'name', $postResponse);
        Assert::assertJsonPathEquals($category->id, 'category.id', $postResponse);
        Assert::assertJsonPathEquals('Крепкий алкоголь', 'category.name', $postResponse);
        Assert::assertJsonPathEquals($group->id, 'category.group.id', $postResponse);
        Assert::assertJsonPathEquals('Алкоголь', 'category.group.name', $postResponse);
    }

    public function testPutSubCategoryActionRoundingUpdated()
    {
        $group = $this->factory()->catalog()->getGroup('Алкоголь');
        $category = $this->factory()->catalog()->createCategory($group->id, 'Водка');
        $subCategory = $this->factory()->catalog()->getSubCategory($category->id, 'Безалкогольная водка');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $getResponse);

        $subCategoryData = array(
            'name' => 'Алкоголь',
            'category' => $category->id,
            'rounding' => 'nearest50',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/subcategories/{$subCategory->id}",
            $subCategoryData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', '0.rounding.name', $getResponse);
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationSubCategoryProvider
     */
    public function testPostSubCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $category = $this->factory()->catalog()->getCategory('Крепкий алкоголь');

        $subCategoryData = $data + array(
            'name' => 'Водка',
            'category' => $category->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationSubCategoryProvider()
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
            'not valid category' => array(
                400,
                array('category' => '1234'),
                array(
                    'errors.children.category.errors.*'
                    =>
                    'Такой категории не существует'
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
                array(
                    'errors.children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailMarkupMin.errors.1' => null,
                ),
            ),
            'not valid markup max not float' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 'bbb'),
                array(
                    'errors.children.retailMarkupMax.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailMarkupMin.errors.1' => null,
                    'errors.children.retailMarkupMin.errors' => null,
                ),
            ),
            'not valid markup, min entered, max not' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => ''),
                array('errors.children.retailMarkupMax.errors.0' => 'Заполните это поле'),
            ),
            'not valid markup, max entered, min not' => array(
                400,
                array('retailMarkupMin' => '', 'retailMarkupMax' => 10),
                array('errors.children.retailMarkupMin.errors.0' => 'Заполните это поле'),
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
            'valid rounding no value, should inherit group value' => array(
                201,
                array('rounding' => null),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'valid rounding empty value, should inherit group value' => array(
                201,
                array('rounding' => ''),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
        );
    }

    public function testUniqueCategoryName()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                'Алкоголь' => array(
                    'Крепкий алкоголь' => array(),
                ),
                'Кисло-молочка' => array(
                    'Молоко' => array(),
                ),
            )
        );

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $catalog['Крепкий алкоголь']->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        // Create first category
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second category with same name in group 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Группа с таким названием уже существует',
            'errors.children.name.errors',
            $postResponse
        );

        $subCategoryData2 = array('category' => $catalog['Молоко']->id) + $subCategoryData;

        // Create category with same name but in category 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData2
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second category with same name in category 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData2
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Группа с таким названием уже существует',
            'errors.children.name.errors',
            $postResponse
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationSubCategoryProvider
     */
    public function testPutSubCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $category = $this->factory()->catalog()->getCategory('Крепкий алкоголь');

        $postData = array(
            'name' => 'Водка',
            'category' => $category->id,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/subcategories/{$subCategoryId}",
            $putData
        );

        $expectedCode = (201 == $expectedCode) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetCategory()
    {
        $category = $this->factory()->catalog()->getCategory();
        $subCategory = $this->factory()->catalog()->createSubCategory($category->id);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($subCategory->id, 'id', $getResponse);
        Assert::assertJsonPathEquals($category->id, 'category.id', $getResponse);
        Assert::assertJsonPathEquals($category->group->id, 'category.group.id', $getResponse);
    }

    public function testGetCategoryNotFound()
    {
        $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => array()
                    )
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/invalidId'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategories()
    {
        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => array(),
                        '1.1.2' => array(),
                        '1.1.3' => array(),
                    )
                ),
                '2' => array(
                    '2.1' => array(
                        '2.1.4' => array(),
                        '2.1.5' => array(),
                    )
                ),
            )
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$catalog['1.1']->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1.1.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.1.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.1.3']->id, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($catalog['2.1.4']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['2.1.5']->id, '*.id', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$catalog['2.1']->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['2.1.4']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2.1.5']->id, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($catalog['1.1.1']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['1.1.2']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalog['1.1.3']->id, '*.id', $getResponse);
    }

    public function testGetCategoriesNotFound()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/123484923423/subcategories'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategoriesEmptyCollection()
    {
        $category = $this->factory()->catalog()->getCategory();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testDeleteSubCategory()
    {
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(204);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200, 'Sub category is soft deletable and should be accessed directly');
    }

    public function testDeleteSubCategoryIsNotExposedInCategorySubCategoriesField()
    {
        $category = $this->factory()->catalog()->getCategory();
        $subCategories = $this->factory()->catalog()->getSubCategories(array('1', '2'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'subCategories.*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategories['1']->id, 'subCategories.*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategories['2']->id, 'subCategories.*.id', $getResponse);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/subcategories/{$subCategories['2']->id}"
        );

        $this->assertResponseCode(204);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/categories/{$category->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, 'subCategories.*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategories['1']->id, 'subCategories.*.id', $getResponse);
        Assert::assertNotJsonPathEquals($subCategories['2']->id, 'subCategories.*.id', $getResponse);
    }

    public function testDeleteNotEmptySubCategory()
    {
        $subCategory = $this->factory()->catalog()->createSubCategory();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);

        $product = $this->factory()->catalog()->createProductByName('1', $subCategory);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}/products"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($product->id, '0.id', $response);

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(409);
        Assert::assertJsonPathEquals(
            'Чтобы удалить группу, нужно сначала удалить все товары в ней',
            'message',
            $response
        );
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array     $requestData
     *
     * @dataProvider accessSubCategoryProvider
     */
    public function testAccessSubCategory($url, $method, $role, $responseCode, array $requestData = array())
    {
        $category = $this->factory()->catalog()->getCategory();
        $subCategory = $this->factory()->catalog()->createSubCategory($category->id);

        $url = str_replace(
            array(
                '__SUBCATEGORY_ID__',
                '__CATEGORY_ID__',
            ),
            array(
                $subCategory->id,
                $category->id,
            ),
            $url
        );

        $accessToken = $this->factory()->oauth()->authAsRole($role);

        $requestData += array(
            'name' => 'Тёмное',
            'category' => $category->id,
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
    public function accessSubCategoryProvider()
    {
        return array(
            /*************************************
             * GET /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * POST /api/1/subcategories
             */
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * PUT /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * DELETE /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_COMMERCIAL_MANAGER,
                204,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * GET /api/1/categories/__CATEGORY_ID__/subcategories
             */
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
        );
    }

    public function testRetailMarkupIsNullOnSubCategoryCreateWithEmptyMarkup()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;
        $subCategoryId = $this->factory()->catalog()->createSubCategory($categoryId, 'Сухое красное божоле')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);
    }

    public function testRetailMarkupIsNotInheritedFromGroupAfterGroupUpdate()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;
        $subCategoryId = $this->factory()->catalog()->createSubCategory($categoryId, 'Сухое красное божоле')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);

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

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);
    }

    public function testRetailMarkupBecomesNullIfNullMarkupPassed()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => null,
            'retailMarkupMax' => null,
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
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
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
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
    public function testGetStoreSubCategoryStoreManagerHasStore($role, $rel)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $subCategory = $this->factory()->catalog()->getSubCategory();
        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $storeManager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($subCategory->id, 'id', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreSubCategoryStoreManagerFromAnotherStore($role, $rel)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $subCategory = $this->factory()->catalog()->getSubCategory();
        $store1 = $this->factory()->store()->getStore('42');
        $store2 = $this->factory()->store()->getStore('43');

        $this->factory()->store()->linkManagers($store1->id, $storeManager->id, $rel);

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store2->id}/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreSubCategoryStoreManagerHasNoStore($role)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $subCategory = $this->factory()->catalog()->getSubCategory();
        $store = $this->factory()->store()->getStore();

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreCategorySubCategoriesStoreManagerHasStore($role, $rel)
    {
        $storeManager = $this->factory()->user()->getUser('vasyaPetrCrause@lighthouse.pro', 'password', $role);

        $store = $this->factory()->store()->getStore();

        $this->factory()->store()->linkManagers($store->id, $storeManager->id, $rel);

        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => array(),
                        '1.1.2' => array(),
                        '1.1.3' => array(),
                    )
                ),
                '2' => array(
                    '2.1' => array(),
                    '2.2' => array(
                        '2.2.1' => array(),
                        '2.2.2' => array()
                    )
                )
            )
        );

        $accessToken = $this->factory()->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/categories/{$catalog['1.1']->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['1.1.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.1.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.1.3']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['1.1']->id, '*.category.id', $getResponse, 3);
        Assert::assertJsonPathEquals($catalog['1']->id, '*.category.group.id', $getResponse, 3);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/categories/{$catalog['2.2']->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalog['2.2.1']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2.2.2']->id, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalog['2.2']->id, '*.category.id', $getResponse, 2);
        Assert::assertJsonPathEquals($catalog['2']->id, '*.category.group.id', $getResponse, 2);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/categories/{$catalog['2.1']->id}/subcategories"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);
    }

    /**
     * @return array
     */
    public function storeRolesProvider()
    {
        return array(
            'store manager' => array(
                User::ROLE_STORE_MANAGER,
                Store::REL_STORE_MANAGERS
            ),
            'department manager' => array(
                User::ROLE_DEPARTMENT_MANAGER,
                Store::REL_DEPARTMENT_MANAGERS
            ),
        );
    }

    public function testRoundingIsInheritedFromGroup()
    {
        $groupId = $this->factory()->catalog()->createGroup('Алкоголь', 'nearest50')->id;
        $categoryId = $this->factory()->catalog()->createCategory($groupId, 'Водка', 'nearest50')->id;

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Водка',
            'category' => $categoryId,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        $subCategoryId = $postResponse['id'];

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId,
            $postData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);
        Assert::assertJsonPathEquals('nearest50', 'category.rounding.name', $getResponse);
        Assert::assertJsonPathEquals('nearest50', 'category.group.rounding.name', $getResponse);
    }

    /**
     * @group unique
     */
    public function testUniqueNameInParallel()
    {
        $category = $this->factory()->catalog()->getCategory();
        $subCategoryData = array(
            'name' => 'Молочка',
            'rounding' => 'nearest1',
            'category' => $category->id
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/subcategories', 'POST', $subCategoryData);
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
            'Группа с таким названием уже существует',
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
        $category = $this->factory()->catalog()->getCategory();
        $subCategoryData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
            'category' => $category->id
        );

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = new SubCategory();

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

        $subCategoryRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Classifier\\SubCategory\\SubCategoryRepository',
            array(),
            array(),
            '',
            false
        );

        $subCategoryRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($subCategory));
        $subCategoryRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($subCategoryRepoMock) {
                $container->set('lighthouse.core.document.repository.classifier.subcategory', $subCategoryRepoMock);
            }
        );

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
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
            'Группа с таким названием уже существует',
            'errors.children.name.errors.0',
            $response
        );
    }
}
