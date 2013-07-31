<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testPostPurchasesAction()
    {
        $this->clearMongoDb();

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');


        $purchaseData = array(
            'products' => array(
                array(
                    'product' => $product1Id,
                    'sellingPrice' => 10.11,
                    'quantity' => 5,
                ),
                array(
                    'product' => $product2Id,
                    'sellingPrice' => 22.36,
                    'quantity' => 1,
                ),
                array(
                    'product' => $product3Id,
                    'sellingPrice' => 5.99,
                    'quantity' => 2,
                ),
            )
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            $purchaseData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        foreach ($purchaseData['products'] as $productData) {
            Assert::assertJsonPathEquals($productData['product'], 'products.*.product.id', $postResponse, 1);
            Assert::assertJsonPathEquals($productData['sellingPrice'], 'products.*.sellingPrice', $postResponse);
            Assert::assertJsonPathEquals($productData['quantity'], 'products.*.quantity', $postResponse);
        }

        Assert::assertNotJsonHasPath('products.*.purchase', $postResponse);

        Assert::assertJsonPathContains($this->getNowDate(), 'createdDate', $postResponse);

        Assert::assertJsonPathEquals($postResponse['createdDate'], 'products.*.createdDate', $postResponse, 3);
    }

    public function testPostPurchasesActionWithCreatedDate()
    {
        $this->clearMongoDb();

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $createdDate = '2013-05-12T15:56:12+0400';

        $purchaseData = array(
            'createdDate' => '2013-05-12T15:56:12',
            'products' => array(
                array(
                    'product' => $product1Id,
                    'sellingPrice' => 10.11,
                    'quantity' => 5,
                ),
                array(
                    'product' => $product2Id,
                    'sellingPrice' => 22.36,
                    'quantity' => 1,
                ),
                array(
                    'product' => $product3Id,
                    'sellingPrice' => 5.99,
                    'quantity' => 2,
                ),
            )
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            $purchaseData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        foreach ($purchaseData['products'] as $productData) {
            Assert::assertJsonPathEquals($productData['product'], 'products.*.product.id', $postResponse, 1);
            Assert::assertJsonPathEquals($productData['sellingPrice'], 'products.*.sellingPrice', $postResponse);
            Assert::assertJsonPathEquals($productData['quantity'], 'products.*.quantity', $postResponse);
        }

        Assert::assertNotJsonHasPath('products.*.purchase', $postResponse);

        Assert::assertJsonPathEquals($createdDate, 'createdDate', $postResponse);

        Assert::assertJsonPathEquals($postResponse['createdDate'], 'products.*.createdDate', $postResponse, 3);
    }

    /**
     * @dataProvider validationPurchaseProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostPurchaseValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $purchaseProductData = array(
            'product' => $productId,
            'sellingPrice' => 7.99,
            'quantity' => 2,
        );

        $purchaseData = $data + array(
            'products' => array($purchaseProductData)
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            $purchaseData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationPurchaseProvider()
    {
        return array(
            'valid empty date' => array(
                201,
                array(),
            ),
            'valid date' => array(
                201,
                array('createdDate' => '2013-12-31 12:44')
            ),
            'not valid date' => array(
                400,
                array('createdDate' => '2013-2sd-31 12:44'),
                array(
                    'children.createdDate.errors.0'
                    =>
                    'Вы ввели неверную дату 2013-2sd-31 12:44, формат должен быть следующий дд.мм.гггг чч:мм'
                )
            ),
            'not valid empty products' => array(
                400,
                array('products' => array()),
                array(
                    'errors.0'
                    =>
                    'Должен присутствовать хотя бы один товар'
                )
            )
        );
    }

    /**
     * @dataProvider validationPurchaseProductProvider
     * 
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostPurchaseProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();
        
        $productId = $this->createProduct();
        
        $purchaseProductData = $data + array(
            'product' => $productId,
            'sellingPrice' => 7.99,
            'quantity' => 2,
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            array(
                'products' => array($purchaseProductData)
            )
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }
    
    public function validationPurchaseProductProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
                array('quantity' => 7),
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                400,
                array('quantity' => 2.5),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть целым числом'
                )
            ),
            /***********************************************************************************************
             * 'price'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('sellingPrice' => 10.89),
            ),
            'valid sellingPrice dot 79.99' => array(
                201,
                array('sellingPrice' => 79.99),
            ),
            'valid sellingPrice coma' => array(
                201,
                array('sellingPrice' => '10,89'),
            ),
            'empty sellingPrice' => array(
                400,
                array('sellingPrice' => ''),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid sellingPrice very float' => array(
                400,
                array('sellingPrice' => '10,898'),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid sellingPrice very float dot' => array(
                400,
                array('sellingPrice' => '10.898'),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid sellingPrice very float with dot' => array(
                201,
                array('sellingPrice' => '10.12')
            ),
            'not valid sellingPrice not a number' => array(
                400,
                array('sellingPrice' => 'not a number'),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                ),
            ),
            'valid sellingPrice zero' => array(
                201,
                array('sellingPrice' => 0),
            ),
            'not valid sellingPrice negative' => array(
                400,
                array('sellingPrice' => -10),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid sellingPrice too big 2 000 000 001' => array(
                400,
                array('sellingPrice' => 2000000001),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid sellingPrice too big 100 000 000' => array(
                400,
                array('sellingPrice' => '100000000'),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid sellingPrice too big 10 000 000' => array(
                201,
                array('sellingPrice' => '10000000'),
            ),
            'not valid sellingPrice too big 10 000 001' => array(
                400,
                array('sellingPrice' => '10000001'),
                array(
                    'children.products.children.0.children.sellingPrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            /***********************************************************************************************
             * 'price'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('product' => 'not_valid_product_id'),
                array(
                    'children.products.children.0.children.product.errors.0'
                    =>
                    'Такого товара не существует'
                ),
            ),
        );
    }

    public function testPostPurchasesActionAmountChange()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $invoiceId = $this->createInvoice();

        $invoiceProductData = array(
            'quantity' => 10,
            'price'    => 17.68,
            'product'  => $productId,
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertResponseCode(201);

        $this->assertProduct($productId, array('amount' => 10));

        $this->createProductPurchase(
            $productId,
            array(
                'sellingPrice' => 19.99,
                'quantity' => 6,
            )
        );

        $this->createProductPurchase(
            $productId,
            array(
                'sellingPrice' => 15.99,
                'quantity' => 4,
            )
        );

        $this->assertProduct($productId, array('amount' => 0));


        $this->createProductPurchase(
            $productId,
            array(
                'sellingPrice' => 17.99,
                'quantity' => 2,
            )
        );

        $this->assertProduct($productId, array('amount' => -2));
    }

    /**
     * @param string $productId
     * @param array $data
     * @return mixed
     */
    protected function createProductPurchase($productId, array $data)
    {
        $purchaseData = array(
            'products' => array(
                $data + array(
                    'product' => $productId,
                    'sellingPrice' => 19.99,
                    'quantity' => 6,
                )
            )
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $purchaseResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            $purchaseData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('products.*.id', $purchaseResponse);
        Assert::assertJsonHasPath('id', $purchaseResponse);
        Assert::assertJsonPathEquals(
            $purchaseData['products'][0]['product'],
            'products.*.product.id',
            $purchaseResponse
        );

        return $purchaseResponse;
    }
}
