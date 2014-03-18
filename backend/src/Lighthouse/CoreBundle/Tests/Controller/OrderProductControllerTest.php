<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class OrderProductControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->setUpStoreDepartmentManager();
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider orderProductValidationProvider
     */
    public function testPostOrderProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $product = $this->createProduct();

        $postData = $data + array(
                'product' => $product,
                'quantity' => 1.11,
            );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders/products?validate=true',
            $postData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    public function orderProductValidationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                200,
                array('quantity' => 7),
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                200,
                array('quantity' => 2.5),
            ),
            'float quantity with coma' => array(
                200,
                array('quantity' => '2,5'),
            ),
            'float quantity very float' => array(
                400,
                array('quantity' => 2.5555),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                    'children.quantity.errors.1'
                    =>
                        null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('quantity' => 'abc'),
                array(
                    'children.quantity.errors.0'
                    =>
                        'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'product'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('product' => 'not_valid_product_id'),
                array(
                    'children.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'children.product.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
        );
    }

    public function testPostOrderProductValidationOutput()
    {
        $product = $this->createProduct();

        $postData = array(
                'product' => $product,
                'quantity' => 1.11,
            );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders/products?validate=true',
            $postData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($postData['quantity'], 'quantity', $response);
        Assert::assertJsonPathEquals($postData['product'], 'product.product.id', $response);
    }

    public function testSearchStoreProductsActionWithRangeMarkup()
    {
        $storeId = $this->factory->store()->getStore('123');
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);

        $group = $this->createGroup('123');
        $category = $this->createCategory($group, '123');
        $subCategory = $this->createSubCategory($category, '123');
        $productData = array(
            'name' => '123',
            'units' => 'gr',
            'barcode' => '123',
            'purchasePrice' => 123,
            'sku' => '123',
            'vat' => 0,
            'vendor' => '',
            'vendorCountry' => '',
            'info' => '',
            'retailMarkupMin' => 0,
            'retailMarkupMax' => 10,
            'subCategory' => $subCategory,
        );
        $product = $this->createProduct($productData, $subCategory);

        $this->factory->createSupplier('123');
        $this->factory->flush();

        $postData = array(
            'product' => $product,
            'quantity' => 1.11,
        );

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders/products?validate=true',
            $postData
        );

        $this->assertResponseCode(200);
    }
}
