<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->setUpStoreDepartmentManager();
    }

    public function testPostOrderAction()
    {
        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');
        $supplier = $this->factory->createSupplier();

        $orderProducts = array(
            array(
                'product' => $product1,
                'quantity' => 3,
            ),
            array(
                'product' => $product2,
                'quantity' => 2,
            ),
            array(
                'product' => $product3,
                'quantity' => 5,
            ),
            array(
                'product' => $product1,
                'quantity' => 1,
            ),
        );

        $orderData = array(
            'supplier' => $supplier,
            'products' => $orderProducts,
        );

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
            $orderData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier, 'supplier', $response);
        foreach ($orderProducts as $orderProduct) {
            Assert::assertJsonPathEquals($orderProduct, 'products.*', $orderProduct);
        }
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationProvider
     */
    public function testPostOrderValidation($expectedCode, array $data, array $assertions = array())
    {
        $product = $this->createProduct();
        $supplier = $this->factory->createSupplier();

        $postData = array(
            'supplier' => $supplier,
            'products' => array(
                array(
                    'product' => $product,
                    'quantity' => 1.11,
                )
            ),
        );

        if (array_key_exists('products', $data)) {
            $postData['products'][0] = $data['products'][0] + $postData['products'][0];
            unset($data['products']);
        }

        $postData = $data + $postData;

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
            $postData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
                array('products' => array(array('quantity' => 7))),
            ),
            'empty quantity' => array(
                400,
                array('products' => array(array('quantity' => ''))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('products' => array(array('quantity' => -10))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('products' => array(array('quantity' => -1))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('products' => array(array('quantity' => 0))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                201,
                array('products' => array(array('quantity' => 2.5))),
            ),
            'float quantity with coma' => array(
                201,
                array('products' => array(array('quantity' => '2,5'))),
            ),
            'float quantity very float' => array(
                400,
                array('products' => array(array('quantity' => 2.5555))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('products' => array(array('quantity' => '2,5555'))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('products' => array(array('quantity' => '2,5555'))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение не должно содержать больше 3 цифр после запятой',
                    'children.products.0.quantity.errors.1'
                    =>
                        null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('products' => array(array('quantity' => 'abc'))),
                array(
                    'children.products.0.quantity.errors.0'
                    =>
                        'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'product'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('products' => array(array('product' => 'not_valid_product_id'))),
                array(
                    'children.products.0.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('products' => array(array('product' => ''))),
                array(
                    'children.products.0.product.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            'not valid supplier' => array(
                400,
                array('supplier' => 'notExists'),
                array(
                    'children.supplier.errors.0'
                    =>
                    'Такого поставщика не существует'
                ),
            ),
            'empty supplier' => array(
                400,
                array('supplier' => ''),
                array(
                    'children.supplier.errors.0'
                    =>
                    'Выберите поставщика'
                ),
            ),
        );
    }
}
