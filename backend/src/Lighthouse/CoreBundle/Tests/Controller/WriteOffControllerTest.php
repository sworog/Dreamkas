<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $date = strtotime('-1 day');
        $writeOffData = array(
            'number' => '431-5678',
            'date' => date('c', $date),
            'products' => array(),
        );

        $writeOffData['products'][] = array(
            'product' => $productId,
            'quantity' => 1,
            'price' => 10.98,
            'cause' => 'Порча'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $writeOffData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonHasPath('products.*.product', $postResponse);
        Assert::assertJsonPathEquals($writeOffData['number'], 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
        Assert::assertJsonPathEquals($productId, 'products.*.product.id', $postResponse, 1);
    }

    /**
     * @dataProvider validationWriteOffProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $writeOffProductData = array(
            'product' => $productId,
            'price' => 7.99,
            'quantity' => 2,
            'cause' => 'Плохо было',
        );

        $writeOffData = $data + array(
            'products' => array($writeOffProductData),
            'date' => '11.07.2012',
            'number' => '1234567',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $writeOffData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationWriteOffProvider()
    {
        return array(
            'not valid empty date' => array(
                400,
                array('date' => ''),
                array(
                    'children.date.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'valid date' => array(
                201,
                array('date' => '2013-12-31')
            ),
            'not valid date' => array(
                400,
                array('date' => '2013-2sd-31'),
                array(
                    'children.date.errors.0'
                    =>
                    'Вы ввели неверную дату 2013-2sd-31, формат должен быть следующий дд.мм.гггг'
                )
            ),
            'not valid empty number' => array(
                400,
                array('number' => ''),
                array(
                    'children.number.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 101 number' => array(
                400,
                array('number' => str_repeat('z', 101)),
                array(
                    'children.number.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'valid long 100 number' => array(
                201,
                array('number' => str_repeat('z', 100)),
            ),
        );
    }

    /**
     * @dataProvider validationWriteOffProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $writeOffProductData = $data + array(
            'product' => $productId,
            'price' => 7.99,
            'quantity' => 2,
            'cause' => 'Сгнил товар'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => array(
                'date' => date('c', strtotime('-1 day')),
                'number' => '1234567',
                'products' => array($writeOffProductData)
            ))
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationWriteOffProductProvider()
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
                array('price' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('price' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('price' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('price' => ''),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('price' => '10,898'),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('price' => '10.898'),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('price' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('price' => 'not a number'),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('price' => 0),
            ),
            'not valid price negative' => array(
                400,
                array('price' => -10),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('price' => 2000000001),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('price' => '100000000'),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('price' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('price' => '10000001'),
                array(
                    'children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            /***********************************************************************************************
             * 'product'
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
            /***********************************************************************************************
             * 'cause'
             ***********************************************************************************************/
            'not valid empty cause' => array(
                400,
                array('cause' => ''),
                array(
                    'children.products.children.0.children.cause.errors.0'
                    =>
                    'Заполните это поле'
                ),
            ),
            'not valid cause long 1001' => array(
                400,
                array('cause' => str_repeat('z', 1001)),
                array(
                    'children.products.children.0.children.cause.errors.0'
                    =>
                    'Не более 1000 символов'
                ),
            ),
            'valid cause long 1000' => array(
                201,
                array('cause' => str_repeat("z", 1000)),
            ),
            'valid cause special symbols' => array(
                201,
                array('cause' => '!@#$%^&^&*QWEQWE}{}":<></.,][;.,`~\=0=-\\'),
            ),
        );
    }
}
