<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{
    public function testPostInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $supplier = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $postResponse);
        Assert::assertJsonPathEquals('ООО "Поставщик"', 'supplier.name', $postResponse);
        Assert::assertJsonPathEquals('2013-03-18T12:56:00+0400', 'acceptanceDate', $postResponse);
        Assert::assertJsonPathEquals('Приемных Н.П.', 'accepter', $postResponse);
        Assert::assertJsonPathEquals('ООО "Магазин"', 'legalEntity', $postResponse);
        Assert::assertJsonPathEquals('1248373', 'supplierInvoiceNumber', $postResponse);
        Assert::assertJsonPathEquals(true, 'includesVAT', $postResponse);
    }

    public function testGetInvoicesAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        for ($i = 0; $i < 5; $i++) {
            $this->factory()
                ->invoice()
                    ->createInvoice(array(), $store->id)
                    ->createInvoiceProduct($productId, 10, 5.99)
                ->flush();
        }

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $getResponse);
    }

    public function testGetInvoicesActionMaxDepth()
    {
        $store = $this->factory()->store()->getStore();
        $products = $this->createProductsBySku(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1'], 9, 9.99)
                ->createInvoiceProduct($products['2'], 19, 19.99)
            ->flush()->id;

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($products['1'], 119, 9.99)
                ->createInvoiceProduct($products['2'], 129, 19.99)
                ->createInvoiceProduct($products['3'], 139, 19.99)
            ->flush()->id;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(0, '0.store.departmentManagers.*', $getResponse);
        Assert::assertJsonPathCount(0, '0.store.storeManagers.*', $getResponse);
        Assert::assertJsonHasPath('0.products.0.product', $getResponse);
        Assert::assertJsonHasPath('0.products.1.product', $getResponse);
        Assert::assertNotJsonHasPath('0.products.0.product.subCategory', $getResponse);
        Assert::assertNotJsonHasPath('0.products.1.product.subCategory', $getResponse);
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testGetInvoice(array $invoiceData, array $assertions)
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $invoice = $this->factory()
            ->invoice()
                ->createInvoice($invoiceData, $store->id)
                ->createInvoiceProduct($productId, 10, 5.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($getResponse, $assertions, true);
    }

    public function postInvoiceDataProvider()
    {
        return array(
            'invoice' => array(
                'data' => array(
                    'acceptanceDate' => '2013-03-18 12:56',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceNumber' => '1248373',
                ),
                // Assertions xpath
                'assertions' => array(
                    'number' => '10001',
                    'acceptanceDate' => '2013-03-18T12:56:00+0400',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceNumber' => '1248373'
                )
            )
        );
    }

    public function testGetInvoiceNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $id = 'not_exists_id';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $id
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('41');
        $store2 = $this->factory()->store()->getStore('43');
        $productId = $this->createProduct();
        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($productId)
            ->flush();

        $accessToken = $this->factory->oauth()->auth($departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $supplier = $this->factory()->supplier()->getSupplier();

        $postData = $data + InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, 10, 5.99);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    /**
      * @dataProvider providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid
     */
    public function testPostInvoiceSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid(array $data)
    {
        $store = $this->factory()->store()->getStore();
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $postData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Вы ввели неверную дату', 'children.acceptanceDate.errors.0', $postResponse);
        Assert::assertNotJsonHasPath('children.supplierInvoiceDate.errors.0', $postResponse);
    }

    /**
     * @return array
     */
    public function providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid()
    {
        return array(
            'supplierInvoiceDate in past' => array(
                array(
                    'acceptanceDate' => 'aaa',
                    'supplierInvoiceDate' => '2012-03-14'
                ),
            ),
            'supplierInvoiceDate in future' => array(
                array(
                    'acceptanceDate' => 'aaa',
                    'supplierInvoiceDate' => '2015-03-14'
                ),
            )
        );
    }

    public function testPutInvoiceAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $supplier1 = $this->factory()->supplier()->getSupplier('ООО "Поставщик"');
        $supplier2 = $this->factory()->supplier()->getSupplier('ООО "Подставщик"');

        $assertions = array(
            'number' => '10001',
            'supplier.name' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18T12:56:00+0400',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
        );

        $postData = InvoiceProductControllerTest::getInvoiceData($supplier1->id, $productId, 10, 5.99);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];

        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $postJson);
        }

        $postData['supplier'] = $supplier2->id;
        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $postData
        );

        $assertions['supplier.name'] = 'ООО "Подставщик"';
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceId, 'id', $postJson);
        foreach ($assertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $putJson);
        }
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'number'
             ***********************************************************************************************/
            'number is skipped' => array(
                201,
                array('number' => '10001'),
            ),
            'valid number 100 chars' => array(
                201,
                array('number' => str_repeat('z', 100)),
            ),
            /***********************************************************************************************
             * 'order'
             ***********************************************************************************************/
            'invalid order' => array(
                400,
                array('order' => '10001'),
                array('children.order.errors.0' => 'Такой заказ не существует'),
            ),
            'empty order' => array(
                201,
                array('order' => ''),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            'supplier is empty' => array(
                400,
                array('supplier' => ''),
                array('children.supplier.errors.0' => 'Выберите поставщика'),
            ),
            'supplier is invalid' => array(
                400,
                array('supplier' => 'aaaa'),
                array('children.supplier.errors.0' => 'Такого поставщика не существует'),
            ),
            'supplier is invalid object' => array(
                400,
                array('supplier' => array('id' => 'aaaa', 'name' => 'ООО "Поставщик"')),
                array('children.supplier.errors.0' => 'Такого поставщика не существует'),
            ),
            /***********************************************************************************************
             * 'accepter'
             ***********************************************************************************************/
            'valid accepter' => array(
                201,
                array('accepter' => 'accepter'),
            ),
            'valid accepter 100 chars' => array(
                201,
                array('accepter' => str_repeat('z', 100)),
            ),
            'empty accepter' => array(
                400,
                array('accepter' => ''),
                array('children.accepter.errors.0' => 'Заполните это поле',),
            ),
            'not valid accepter too long' => array(
                400,
                array('accepter' => str_repeat("z", 105)),
                array('children.accepter.errors.0' => 'Не более 100 символов'),
            ),
            /***********************************************************************************************
             * 'legalEntity'
             ***********************************************************************************************/
            'valid legalEntity' => array(
                201,
                array('legalEntity' => 'legalEntity'),
            ),
            'valid legalEntity 300 chars' => array(
                201,
                array('legalEntity' => str_repeat('z', 300)),
            ),
            'empty legalEntity' => array(
                400,
                array('legalEntity' => ''),
                array('children.legalEntity.errors.0' => 'Заполните это поле'),
            ),
            'not valid legalEntity too long' => array(
                400,
                array('legalEntity' => str_repeat("z", 305)),
                array('children.legalEntity.errors.0' => 'Не более 300 символов'),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceNumber'
             ***********************************************************************************************/
            'valid supplierInvoiceNumber' => array(
                201,
                array('supplierInvoiceNumber' => 'supplierInvoiceNumber'),
            ),
            'valid supplierInvoiceNumber 100 chars' => array(
                201,
                array('supplierInvoiceNumber' => str_repeat('z', 100)),
            ),
            'empty supplierInvoiceNumber' => array(
                400,
                array('supplierInvoiceNumber' => ''),
                array('children.supplierInvoiceNumber.errors.0' => 'Заполните это поле'),
            ),
            'not valid supplierInvoiceNumber too long' => array(
                400,
                array('supplierInvoiceNumber' => str_repeat("z", 105)),
                array('children.supplierInvoiceNumber.errors.0' => 'Не более 100 символов'),
            ),
            /***********************************************************************************************
             * 'acceptanceDate'
             ***********************************************************************************************/
            'valid acceptanceDate 2013-03-26T12:34:56' => array(
                201,
                array('acceptanceDate' => '2013-03-26T12:34:56'),
                array("acceptanceDate" => '2013-03-26T12:34:56+0400')
            ),
            'valid acceptanceDate 2013-03-26' => array(
                201,
                array('acceptanceDate' => '2013-03-26'),
                array("acceptanceDate" => '2013-03-26T00:00:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34' => array(
                201,
                array('acceptanceDate' => '2013-03-26 12:34'),
                array("acceptanceDate" => '2013-03-26T12:34:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34:45' => array(
                201,
                array('acceptanceDate' => '2013-03-26 12:34:45'),
                array("acceptanceDate" => '2013-03-26T12:34:45+0400')
            ),
            'empty acceptanceDate' => array(
                400,
                array('acceptanceDate' => ''),
                array('children.acceptanceDate.errors.0' => 'Заполните это поле'),
            ),
            'not valid acceptanceDate 2013-02-31' => array(
                400,
                array('acceptanceDate' => '2013-02-31'),
                array('children.acceptanceDate.errors.0' => 'Вы ввели неверную дату'),
            ),
            'not valid acceptanceDate aaa' => array(
                400,
                array('acceptanceDate' => 'aaa'),
                array(
                    'children.acceptanceDate.errors.0'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid acceptanceDate __.__.____ __:__' => array(
                400,
                array('acceptanceDate' => '__.__.____ __:__'),
                array(
                    'children.acceptanceDate.errors.0'
                    =>
                        'Вы ввели неверную дату',
                ),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceDate'
             ***********************************************************************************************/
            'supplierInvoiceDate should not be present' => array(
                400,
                array('supplierInvoiceDate' => '2013-02-31'),
                array(
                    'errors.0'
                    =>
                    'Эта форма не должна содержать дополнительных полей: "supplierInvoiceDate"',
                ),
            ),
            /***********************************************************************************************
             * 'createdDate'
             ***********************************************************************************************/
            'not valid createdDate' => array(
                400,
                array('createdDate' => '2013-03-26T12:34:56'),
                array(
                    'errors.0'
                    =>
                    'Эта форма не должна содержать дополнительных полей',
                ),
            ),
        );
    }

    public function testDepartmentManagerCantGetInvoiceFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('41');
        $store2 = $this->factory()->store()->getStore('43');
        $productId = $this->createProduct();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($productId)
            ->flush();
        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store2->id)
                ->createInvoiceProduct($productId)
            ->flush();

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store1->id . '/invoices/' . $invoice1->id
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store2->id . '/invoices/' . $invoice2->id
        );

        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store1->id . '/invoices/' . $invoice1->id
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store2->id . '/invoices/' . $invoice2->id
        );

        $this->assertResponseCode(200);
    }

    /**
     * @param string $query
     * @param int $count
     * @param array $assertions
     *
     * @dataProvider invoiceFilterProvider
     */
    public function testInvoicesFilter($query, $count, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');
        $productId3 = $this->createProduct('333');

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => 'ФРГ-1945'), $store->id)
                ->createInvoiceProduct($productId1, 10, 6.98, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => '10001'), $store->id)
                ->createInvoiceProduct($productId2, 5, 10.12, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('supplierInvoiceNumber' => '10003'), $store->id)
                ->createInvoiceProduct($productId3, 7, 67.32, $store->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => $query)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount($count, '*.id', $response);
        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function invoiceFilterProvider()
    {
        return array(
            'one by number' => array(
                '10002',
                1,
                array(
                    '0.number' => '10002',
                    '0._meta.highlights.number' => true,
                )
            ),
            'one by supplierInvoiceNumber' => array(
                'ФРГ-1945',
                1,
                array(
                    '0.supplierInvoiceNumber' => 'ФРГ-1945',
                    '0._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'one by both number and supplierInvoiceNumber' => array(
                '10003',
                1,
                array(
                    '0.supplierInvoiceNumber' => '10003',
                    '0.number' => '10003',
                    '0._meta.highlights.number' => true,
                    '0._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'none found: not existing number' => array(
                '1234',
                0,
            ),
            'none found: empty number' => array(
                '',
                0,
            ),
            'none found: partial number' => array(
                '1000',
                0,
            ),
            'two: one by number and one by supplierInvoiceNumber' => array(
                '10001',
                2,
                array(
                    '0.number' => '10001',
                    '1.supplierInvoiceNumber' => '10001',
                    '0._meta.highlights.number' => true,
                    '1._meta.highlights.supplierInvoiceNumber' => true,
                )
            ),
            'one by number check invoice products' => array(
                '10002',
                1,
                array(
                    '0.number' => '10002',
                    '0._meta.highlights.number' => true,
                )
            ),
        );
    }

    public function testInvoicesFilterOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('111');
        $productId2 = $this->createProduct('222');

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => 'ФРГ-1945',
                        'acceptanceDate' => '2013-03-17T16:12:33+0400',
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId1, 10, 6.98)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => '10001',
                        'acceptanceDate' => '2013-03-16T14:54:23+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId2, 5, 10.12)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => '10001')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('10001', '0.number', $response);
        Assert::assertJsonPathEquals('10002', '1.number', $response);

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => 'ФРГ-1945',
                        'acceptanceDate' => '2013-03-15T16:12:33+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId1, 10, 6.98, $store->id)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array(
                        'supplierInvoiceNumber' => '10003',
                        'acceptanceDate' => '2013-03-16T14:54:23+0400'
                    ),
                    $store->id
                )
                ->createInvoiceProduct($productId2, 5, 10.12, $store->id)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices',
            null,
            array('numberOrSupplierInvoiceNumber' => '10003')
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('10004', '0.number', $response);
        Assert::assertJsonPathEquals('10003', '1.number', $response);
    }

    public function testInvoiceWithVATFields()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId1 = $this->createProduct(array('sku' => '111', 'vat' => '10'));
        $productId2 = $this->createProduct(array('sku' => '222', 'vat' => '18'));

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId1, 99.99, 36.78);
        $invoiceData['includesVAT'] = true;
        $invoiceData['products'][1] = array(
            'product' => $productId2,
            'quantity' => 10.77,
            'priceEntered' => 6.98
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3752.8, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3407.42, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(345.39, 'totalAmountVAT', $response);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);
    }

    /**
     * Проверяем что указав цену без НДС получим данные соответствующие данным теста выше
     */
    public function testInvoiceWithVATFieldsWithoutVAT()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId1 = $this->createProduct(array('sku' => '111', 'vat' => '10'));
        $productId2 = $this->createProduct(array('sku' => '222', 'vat' => '18'));

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId1, 99.99, 33.44);
        $invoiceData['includesVAT'] = false;
        $invoiceData['products'][1] = array(
            'product' => $productId2,
            'quantity' => 10.77,
            'priceEntered' => 5.92
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3752.8, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3407.42, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(345.39, 'totalAmountVAT', $response);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);
    }

    public function testInvoiceChangeIncludesVAT()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('sku' => '111', 'vat' => '10'));
        $this->createProduct(array('sku' => '222', 'vat' => '18'));
        $supplier = $this->factory()->supplier()->getSupplier();

        $invoiceData1 = array(
            'supplier' => $supplier->id,
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
            'includesVAT' => true,
            'products' => array(
                array(
                    'product' => $productId1,
                    'quantity' => '99.99',
                    'priceEntered' => '36.78'
                )
            )
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData1
        );

        $this->assertResponseCode(201);

        $invoiceId1 = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'includesVAT', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $response);

        $invoiceData1['includesVAT'] = false;

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1,
            $invoiceData1
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(false, 'includesVAT', $response);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $response);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $response);
    }

    public function testProductSubCategoryIsNotExposed()
    {
        $storeId = $this->factory()->store()->getStoreId();

        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $storeId)
                ->createInvoiceProduct($productIds['1'], 2, 9.99, $storeId)
                ->createInvoiceProduct($productIds['2'], 3, 4.99, $storeId)
                ->createInvoiceProduct($productIds['3'], 2, 1.95, $storeId)
            ->flush();

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId .'/invoices/' . $invoice->id
        );

        $this->assertResponseCode(200);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $getResponse);
    }

    public function testValidationGroup()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct('1');

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, 10, 5.99);
        $invoiceData['acceptanceDate'] = '';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Заполните это поле', 'children.acceptanceDate.errors.0', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Заполните это поле', 'children.acceptanceDate.errors.0', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertNotJsonHasPath('children.acceptanceDate.errors.0', $response);
        Assert::assertNotJsonHasPath('acceptanceData', $response);
        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals('59.90', 'sumTotal', $response);

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertNotJsonHasPath('children.acceptanceDate.errors.0', $response);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    /**
     * @dataProvider validationGroupDataProvider
     * @param array $invalidData
     * @param string $expectedErrorPath
     * @param string $expectedErrorMessage
     * @param $expectedEmptyField
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testValidationGroupWithInvalidSupplier(
        array $invalidData,
        $expectedErrorPath,
        $expectedErrorMessage,
        $expectedEmptyField
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct('1');

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, 10, 5.99);
        $invoiceData = $invalidData + $invoiceData;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals($expectedErrorMessage, $expectedErrorPath, $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals($expectedErrorMessage, $expectedErrorPath, $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertNotJsonHasPath($expectedErrorPath, $response);
        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals('59.90', 'sumTotal', $response);
        Assert::assertNotJsonHasPath($expectedEmptyField, $response);

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertNotJsonHasPath($expectedErrorPath, $response);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function validationGroupDataProvider()
    {
        return array(
            'not found supplier' => array(
                array('supplier' => 'aaaa'),
                'children.supplier.errors.0',
                'Такого поставщика не существует',
                'supplier'
            ),
            'empty supplier' => array(
                array('supplier' => ''),
                'children.supplier.errors.0',
                'Выберите поставщика',
                'supplier'
            ),
            'empty acceptance date' => array(
                array('acceptanceDate' => ''),
                'children.acceptanceDate.errors.0',
                'Заполните это поле',
                'acceptanceDate'
            ),
            /*
            'invalid acceptance date' => array(
                array('acceptanceDate' => 'aaaa'),
                'children.acceptanceDate.errors.0',
                'Заполните это поле',
                'acceptanceDate'
            )
            */
        );
    }

    /**
     * @dataProvider totalsCalculationOnPostWithValidationGroupDataProvider
     * @param int $VAT
     * @param bool $includesVAT
     * @param float $quantity
     * @param float $price
     * @param float $expectedSumTotal
     * @param float $expectedSumTotalWithoutVAT
     * @param float $expectedTotalAmountVAT
     * @param float $expectedPrice
     * @param float $expectedPriceWithoutVAT
     * @param float $expectedAmountVAT
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPost(
        $VAT,
        $includesVAT,
        $quantity,
        $price,
        $expectedSumTotal,
        $expectedSumTotalWithoutVAT,
        $expectedTotalAmountVAT,
        $expectedPrice,
        $expectedPriceWithoutVAT,
        $expectedAmountVAT
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct(array('vat' => $VAT));

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, $quantity, $price);
        $invoiceData['includesVAT'] = $includesVAT;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );
        $this->assertResponseCode(201);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($expectedSumTotal, 'sumTotal', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'products.0.totalPrice', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'products.0.totalPriceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'products.0.totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedPrice, 'products.0.price', $response);
        Assert::assertJsonPathEquals($expectedPriceWithoutVAT, 'products.0.priceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedAmountVAT, 'products.0.amountVAT', $response);
    }

    /**
     * @dataProvider totalsCalculationOnPostWithValidationGroupDataProvider
     * @param int $VAT
     * @param bool $includesVAT
     * @param float $quantity
     * @param float $price
     * @param float $expectedSumTotal
     * @param float $expectedSumTotalWithoutVAT
     * @param float $expectedTotalAmountVAT
     * @param $expectedPrice
     * @param $expectedPriceWithoutVAT
     * @param $expectedAmountVAT
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPut(
        $VAT,
        $includesVAT,
        $quantity,
        $price,
        $expectedSumTotal,
        $expectedSumTotalWithoutVAT,
        $expectedTotalAmountVAT,
        $expectedPrice,
        $expectedPriceWithoutVAT,
        $expectedAmountVAT
    ) {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct(array('vat' => $VAT));

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, '5', '5.00');
        $invoiceData['includesVAT'] = $includesVAT;

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $invoiceData['products'][0]['quantity'] = $quantity;
        $invoiceData['products'][0]['priceEntered'] = $price;
        $invoiceData['includesVAT'] = $includesVAT;

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId . '?validate=true&validationGroups=products',
            $invoiceData
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($invoiceId, 'id', $response);
        Assert::assertJsonPathEquals($expectedSumTotal, 'sumTotal', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'sumTotalWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedSumTotal, 'products.0.totalPrice', $response);
        Assert::assertJsonPathEquals($expectedSumTotalWithoutVAT, 'products.0.totalPriceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedTotalAmountVAT, 'products.0.totalAmountVAT', $response);

        Assert::assertJsonPathEquals($expectedPrice, 'products.0.price', $response);
        Assert::assertJsonPathEquals($expectedPriceWithoutVAT, 'products.0.priceWithoutVAT', $response);
        Assert::assertJsonPathEquals($expectedAmountVAT, 'products.0.amountVAT', $response);
    }

    /**
     * @return array
     */
    public function totalsCalculationOnPostWithValidationGroupDataProvider()
    {
        return array(
            'with VAT 10%' => array(
                'VAT' => 10,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '54.40',
                'expectedTotalAmountVAT' => '5.50',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.44',
                'expectedAmountVAT' => '0.55',
            ),
            'without VAT 10%' => array(
                'VAT' => 10,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '65.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '6.00',
                'expectedPrice' => '6.59',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.60',
            ),
            'with VAT 18%' => array(
                'VAT' => 18,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '50.80',
                'expectedTotalAmountVAT' => '9.10',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.08',
                'expectedAmountVAT' => '0.91',
            ),
            'without VAT 18%' => array(
                'VAT' => 18,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '70.70',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '10.80',
                'expectedPrice' => '7.07',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '1.08',
            ),
            'with VAT 0%' => array(
                'VAT' => 0,
                'includesVAT' => true,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '0.00',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.00',
            ),
            'without VAT 0%' => array(
                'VAT' => 0,
                'includesVAT' => false,
                'quantity' => 10,
                'priceEntered' => '5.99',
                'expectedSumTotal' => '59.90',
                'expectedSumTotalWithoutVAT' => '59.90',
                'expectedTotalAmountVAT' => '0.00',
                'expectedPrice' => '5.99',
                'expectedPriceWithoutVAT' => '5.99',
                'expectedAmountVAT' => '0.00',
            ),
        );
    }

    public function testProductsBecomeEmptyOnPutOnAcceptanceDateChange()
    {
        $store = $this->factory()->store()->createStore();
        $productId = $this->createProduct();
        $supplier = $this->factory()->supplier()->getSupplier();

        $invoiceData = InvoiceProductControllerTest::getInvoiceData($supplier->id, $productId, 10, 5.99);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonPathCount(1, 'products.*', $postResponse);
        $invoiceId = $postResponse['id'];

        $invoiceData['acceptanceDate'] = '16.04.2014 15:09';
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, 'products.*', $putResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, 'products.*', $getResponse);
        $this->assertSame($putResponse['products'][0], $getResponse['products'][0]);
    }

    public function testGetInvoiceDataByOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'sku' => '10001'));
        $productId2 = $this->createProduct(array('purchasePrice' => '22.22', 'name' => 'Продукт 2', 'sku' => '10002'));
        $productId3 = $this->createProduct(array('purchasePrice' => '33.33', 'name' => 'Продукт 3', 'sku' => '10003'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()->createOrder($store, $supplier, '2014-04-16 17:39');
        $this->factory()->createOrderProduct($order, $productId1, 11);
        $this->factory()->createOrderProduct($order, $productId2, 22);
        $this->factory()->createOrderProduct($order, $productId3, 33);
        $this->factory()->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id . '/invoice'
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $response);
        Assert::assertJsonPathCount('3', 'products.*', $response);

        Assert::assertJsonPathEquals($productId1, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('11', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('11.11', 'products.0.price', $response);

        Assert::assertJsonPathEquals($productId2, 'products.1.product.id', $response);
        Assert::assertJsonPathEquals('22', 'products.1.quantity', $response);
        Assert::assertJsonPathEquals('22.22', 'products.1.price', $response);

        Assert::assertJsonPathEquals($productId3, 'products.2.product.id', $response);
        Assert::assertJsonPathEquals('33', 'products.2.quantity', $response);
        Assert::assertJsonPathEquals('33.33', 'products.2.price', $response);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertNotJsonHasPath('number', $response);
        Assert::assertNotJsonHasPath('legalEntity', $response);
        Assert::assertNotJsonHasPath('accepter', $response);
        Assert::assertNotJsonHasPath('acceptanceDate', $response);
        Assert::assertNotJsonHasPath('supplierInvoiceNumber', $response);
    }

    public function testGetInvoiceDataByOrderNotFoundInAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');

        $supplier = $this->factory()->supplier()->getSupplier();

        $order1 = $this->factory()->createOrder($store1, $supplier);
        $this->factory()->createOrderProduct($order1, $productId1, 11);
        $this->factory()->flush();

        $order2 = $this->factory()->createOrder($store2, $supplier);
        $this->factory()->createOrderProduct($order2, $productId2, 22);
        $this->factory()->flush();

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $this->clientJsonRequest(
            $accessToken1,
            'GET',
            '/api/1/stores/' . $store1->id . '/orders/' . $order2->id . '/invoice'
        );
        $this->assertResponseCode(404);

        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);
        $this->clientJsonRequest(
            $accessToken2,
            'GET',
            '/api/1/stores/' . $store2->id . '/orders/' . $order1->id . '/invoice'
        );
        $this->assertResponseCode(404);
    }

    public function testCreateInvoiceWithOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'sku' => '10001'));
        $productId2 = $this->createProduct(array('purchasePrice' => '22.22', 'name' => 'Продукт 2', 'sku' => '10002'));
        $productId3 = $this->createProduct(array('purchasePrice' => '33.33', 'name' => 'Продукт 3', 'sku' => '10003'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()->createOrder($store, $supplier, '2014-04-16 17:39');
        $this->factory()->createOrderProduct($order, $productId1, 11);
        $this->factory()->createOrderProduct($order, $productId2, 22);
        $this->factory()->createOrderProduct($order, $productId3, 33);
        $this->factory()->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($order->id, 'order.id', $response);
        Assert::assertJsonPathEquals('10001', 'number', $response);
        Assert::assertJsonPathCount(3, 'products.*', $response);
    }

    public function testDuplicateInvoiceOnOrderCreate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'sku' => '10001'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()->createOrder($store, $supplier, '2014-04-16 17:39');
        $this->factory()->createOrderProduct($order, $productId, 11);
        $this->factory()->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Накладная по этому заказу уже существует', 'children.order.errors.0', $response);
    }

    public function testInvoiceIsExposedInOrder()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('purchasePrice' => '11.11', 'name' => 'Продукт 1', 'sku' => '10001'));

        $supplier = $this->factory()->supplier()->getSupplier();

        $order = $this->factory()->createOrder($store, $supplier, '2014-04-16 17:39');
        $this->factory()->createOrderProduct($order, $productId, 11);
        $this->factory()->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceData = $this->getInvoiceDataByOrder($order);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($order->id, 'order.id', $response);
        $invoiceId = $response['id'];

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $response);
        Assert::assertJsonPathCount(1, 'invoice.products.*', $response);
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function getInvoiceDataByOrder(Order $order)
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($order->store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $order->store->id . '/orders/' . $order->id . '/invoice'
        );
        $this->assertResponseCode(200);
        $invoiceData = InvoiceProductControllerTest::getInvoiceData($response['supplier']['id'], null, null, null);
        $invoiceData['order'] = $response['order']['id'];
        foreach ($response['products'] as $key => $productData) {
            $invoiceData['products'][$key] = array(
                'product' => $productData['product']['id'],
                'quantity' => $productData['quantity'],
                'priceEntered' => $productData['priceEntered'],
            );
        }
        return $invoiceData;
    }
}
