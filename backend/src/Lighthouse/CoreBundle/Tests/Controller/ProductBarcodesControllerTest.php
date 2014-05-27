<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ProductBarcodesControllerTest extends WebTestCase
{
    /**
     * @dataProvider putActionProvider
     * @param array $data
     * @param $expectedCode
     * @param array $assertions
     * @param array $productAssertions
     */
    public function testPutAction(array $data, $expectedCode, array $assertions, array $productAssertions = array())
    {
        $productId = $this->createProduct();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId . '/barcodes',
            $data
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions);

        if (!empty($productAssertions)) {
            $this->assertProduct($productId, $productAssertions);
        }
    }

    /**
     * @dataProvider putActionProvider
     * @param array $data
     * @param $expectedCode
     * @param array $assertions
     * @param array $productAssertions
     */
    public function testPutActionValidation(
        array $data,
        $expectedCode,
        array $assertions,
        array $productAssertions = array()
    ) {
        $productId = $this->createProduct();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId . '/barcodes?validate=true',
            $data
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions);

        if (!empty($productAssertions)) {
            $this->assertProduct($productId, array('barcodes' => array()));
        }
    }

    /**
     * @return array
     */
    public function putActionProvider()
    {
        return array(
            // Positive
            'valid all fields filled' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '12',
                            'price' => '28.09'
                        )
                    )
                ),
                200,
                array(
                    '0.barcode' => '1111',
                    '0.quantity' => 12,
                    '0.price' => 28.09
                ),
                array(
                    'barcodes.0.barcode' => '1111',
                    'barcodes.0.quantity' => 12,
                    'barcodes.0.price' => 28.09,
                    'barcodes.1' => null
                )
            ),
            'valid price missing' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '1',
                            'price' => ''
                        )
                    )
                ),
                200,
                array(
                    '0.barcode' => '1111',
                    '0.quantity' => '1',
                    '0.price' => null
                ),
                array(
                    'barcodes.0.barcode' => '1111',
                    'barcodes.0.quantity' => '1',
                    'barcodes.0.price' => null,
                    'barcodes.1' => null
                )
            ),
            'valid barcode length 200' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => str_repeat('1', 200),
                            'quantity' => '1',
                            'price' => ''
                        )
                    )
                ),
                200,
                array(
                    '0.barcode' => str_repeat('1', 200),
                    '0.quantity' => '1',
                    '0.price' => null
                ),
                array(
                    'barcodes.0.barcode' => str_repeat('1', 200),
                    'barcodes.0.quantity' => '1',
                    'barcodes.0.price' => null,
                    'barcodes.1' => null
                )
            ),
            'valid quantity 0.001' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => 'aaaa',
                            'quantity' => '0.001',
                            'price' => ''
                        )
                    )
                ),
                200,
                array(
                    '0.barcode' => 'aaaa',
                    '0.quantity' => 0.001,
                    '0.price' => null
                ),
                array(
                    'barcodes.0.barcode' => 'aaaa',
                    'barcodes.0.quantity' => 0.001,
                    'barcodes.0.price' => null,
                    'barcodes.1' => null
                )
            ),
            'valid 2 barcodes' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '1',
                            'price' => ''
                        ),
                        array(
                            'barcode' => '1112',
                            'quantity' => '1',
                            'price' => ''
                        )
                    )
                ),
                200,
                array(
                    '0.barcode' => '1111',
                    '0.quantity' => '1',
                    '0.price' => null,
                    '1.barcode' => '1112',
                    '1.quantity' => '1',
                    '1.price' => null,
                    '2' => null,
                ),
                array(
                    'barcodes.0.barcode' => '1111',
                    'barcodes.0.quantity' => '1',
                    'barcodes.0.price' => null,
                    'barcodes.1.barcode' => '1112',
                    'barcodes.1.quantity' => '1',
                    'barcodes.1.price' => null,
                    'barcodes.2' => null
                )
            ),
            // Negative
            // Barcode
            'invalid barcode missing' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '',
                            'quantity' => '12',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.barcode.errors.0' => 'Заполните это поле',
                ),
            ),
            'invalid barcode length 201' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => str_repeat('1', 201),
                            'quantity' => '12',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.barcode.errors.0' => 'Не более 200 символов',
                ),
            ),
            // Quantity
            'invalid quantity missing' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => ''
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'invalid quantity precision coma' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '12,1234',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                ),
            ),
            'invalid quantity precision dot' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '12.0001',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                ),
            ),
            'invalid quantity not number' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => 'aaaa',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть числом',
                ),
            ),
            'invalid quantity 0' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '0',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0',
                ),
            ),
            'invalid quantity -1.09' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '-1.09',
                            'price' => '28.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0',
                ),
            ),
            // Price
            'invalid price precision coma' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => '12,124'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой',
                ),
            ),
            'invalid price precision dot' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => '0.001'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                    'children.barcodes.children.0.children.price.errors.1'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой',
                ),
            ),
            'invalid price not number' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => 'цена'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.price.errors.0'
                    =>
                    'Значение должно быть числом',
                ),
            ),
            'invalid price 0' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => '0'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                ),
            ),
            'invalid negative price -1.09' => array(
                array(
                    'barcodes' => array(
                        array(
                            'barcode' => '1111',
                            'quantity' => '',
                            'price' => '-1.09'
                        )
                    )
                ),
                400,
                array(
                    'children.barcodes.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю',
                ),
            ),
        );
    }

    /**
     * @dataProvider barcodeUniqueProvider
     * @param array $barcodes
     * @param array $assertions
     */
    public function testExtraBarcodeUniqueAnotherProduct(array $barcodes, array $assertions)
    {
        $productId1 = $this->createProduct(array('name' => 'Кефир', 'barcode' => '11111'));
        $productId2 = $this->createProduct(array('name' => 'Молоко', 'barcode' => '11112'));

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId1 . '/barcodes',
            array(
                'barcodes' => array(
                    array('barcode' => '54492653', 'quantity' => '1'),
                    array('barcode' => '54492654', 'quantity' => '1'),
                )
            )
        );
        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId2 . '/barcodes',
            array(
                'barcodes' => $barcodes,
            )
        );
        $this->assertResponseCode(400);
        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function barcodeUniqueProvider()
    {
        return array(
            'extra barcode outer' => array(
                array(
                    array('barcode' => '54492653', 'quantity' => '1')
                ),
                array(
                    'children.barcodes.children.0.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в продукте [10001] "Кефир"'
                )
            ),
            'main barcode outer' => array(
                array(
                    array('barcode' => '11111', 'quantity' => '1')
                ),
                array(
                    'children.barcodes.children.0.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в продукте [10001] "Кефир"'
                )
            ),
            'all barcodes outer' => array(
                array(
                    array('barcode' => '11111', 'quantity' => '1'),
                    array('barcode' => '54492653', 'quantity' => '1'),
                    array('barcode' => '54492654', 'quantity' => '1'),
                ),
                array(
                    'children.barcodes.children.0.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в продукте [10001] "Кефир"',
                    'children.barcodes.children.1.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в продукте [10001] "Кефир"',
                    'children.barcodes.children.2.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в продукте [10001] "Кефир"',
                )
            ),
            'main barcode inner' => array(
                array(
                    array('barcode' => '11112', 'quantity' => '1')
                ),
                array(
                    'children.barcodes.children.0.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в этом продукте'
                )
            ),
            'extra barcode inner' => array(
                array(
                    array('barcode' => '666', 'quantity' => '1'),
                    array('barcode' => '666', 'quantity' => '1'),
                ),
                array(
                    'children.barcodes.children.0.children.barcode.errors.0'
                    =>
                    null,
                    'children.barcodes.children.1.children.barcode.errors.0'
                    =>
                    'Штрихкод уже используется в этом продукте'
                )
            ),
        );
    }
}
