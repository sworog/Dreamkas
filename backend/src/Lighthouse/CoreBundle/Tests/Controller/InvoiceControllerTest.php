<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->initStoreDepartmentManager();
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testPostInvoiceAction(array $invoiceData, array $assertions = array())
    {
        $assertions['createdDate'] = $this->getNowDate();
        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testGetInvoicesAction(array $invoiceData)
    {
        for ($i = 0; $i < 5; $i++) {
            $invoiceData['sku'] = '12122004' . $i;
            $this->createInvoice($invoiceData, $this->storeId, $this->departmentManager);
        }

        $accessToken = $this->auth($this->departmentManager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $getResponse);
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testGetInvoice(array $invoiceData, array $assertions)
    {
        $assertions['createdDate'] = $this->getNowDate();
        $id = $this->createInvoice($invoiceData, $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $id
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($getResponse, $assertions, true);
    }

    public function postInvoiceDataProvider()
    {
        return array(
            'invoice' => array(
                'data' => array(
                    'sku' => 'sdfwfsf232',
                    'supplier' => 'ООО "Поставщик"',
                    'acceptanceDate' => '2013-03-18 12:56',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceSku' => '1248373',
                    'supplierInvoiceDate' => '17.03.2013'
                ),
                // Assertions xpath
                'assertions' => array(
                    'sku' => 'sdfwfsf232',
                    'supplier' => 'ООО "Поставщик"',
                    'acceptanceDate' => '2013-03-18T12:56:00+0400',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceSku' => '1248373',
                    'supplierInvoiceDate' => '2013-03-17T00:00:00+0400',
                    'createdDate' => '#set now date value in test to avoid test failure#',
                )
            )
        );
    }

    public function testGetInvoiceNotFound()
    {
        $id = 'not_exists_id';

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $id
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceNotFoundInAnotherStore()
    {
        $storeId2 = $this->createStore('43');
        $this->linkDepartmentManagers($storeId2, $this->departmentManager->id);

        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices',
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
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $accessToken = $this->auth($this->departmentManager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices',
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

    /**
     * @dataProvider putInvoiceDataProvider
     */
    public function testPutInvoiceAction(
        array $postData,
        array $postAssertions,
        array $putData,
        $expectedCode,
        array $putAssertions
    ) {

        $postAssertions['createdDate'] = $this->getNowDate();
        if (isset($putAssertions['createdDate'])) {
            $putAssertions['createdDate'] = $this->getNowDate();
        }

        $accessToken = $this->auth($this->departmentManager);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];
        foreach ($postAssertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $postJson);
        }

        $putData += $postData;
        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId,
            $putData
        );

        $this->assertResponseCode($expectedCode);
        Assert::assertJsonPathEquals($invoiceId, 'id', $postJson);
        foreach ($putAssertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $putJson);
        }
    }

    public function putInvoiceDataProvider()
    {
        $data = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013'
        );
        $assertions = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18T12:56:00+0400',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '2013-03-17T00:00:00+0400',
            'createdDate' => '#set now date value in test to avoid test failure#',
        );

        return array(
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplier' => 'ООО "Подставщик"',
                ),
                'expectedCode' => 200,
                'putAssertions' => array(
                    'supplier' => 'ООО "Подставщик"',
                ) + $assertions,
            ),
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplierInvoiceDate' => '16.03.2013',
                ),
                'expectedCode' => 200,
                'putAssertions' => array(
                    'supplierInvoiceDate' => '2013-03-16T00:00:00+0400',
                ),
            ),
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplierInvoiceDate' => '19.03.2013',
                ),
                'expectedCode' => 400,
                'putAssertions' => array(
                    'children.supplierInvoiceDate.errors.0' => 'Дата накладной не должна быть старше даты приемки',
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'valid sku' => array(
                201,
                array('sku' => 'sku'),
            ),
            'valid sku 100 chars' => array(
                201,
                array('sku' => str_repeat('z', 100)),
            ),
            'empty sku' => array(
                400,
                array('sku' => ''),
                array(
                    'children.sku.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 105)),
                array('children.sku.errors.0' => 'Не более 100 символов'),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            'valid supplier' => array(
                201,
                array('supplier' => 'supplier'),
            ),
            'valid supplier 300 chars' => array(
                201,
                array('supplier' => str_repeat('z', 300)),
            ),
            'empty supplier' => array(
                400,
                array('supplier' => ''),
                array('children.supplier.errors.0' => 'Заполните это поле'),
            ),
            'not valid supplier too long' => array(
                400,
                array('supplier' => str_repeat("z", 305)),
                array('children.supplier.errors.0' => 'Не более 300 символов'),
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
             * 'supplierInvoiceSku'
             ***********************************************************************************************/
            'valid supplierInvoiceSku' => array(
                201,
                array('supplierInvoiceSku' => 'supplierInvoiceSku'),
            ),
            'valid supplierInvoiceSku 100 chars' => array(
                201,
                array('supplierInvoiceSku' => str_repeat('z', 100)),
            ),
            'empty supplierInvoiceSku' => array(
                201,
                array('supplierInvoiceSku' => ''),
            ),
            'not valid supplierInvoiceSku too long' => array(
                400,
                array('supplierInvoiceSku' => str_repeat("z", 105)),
                array('children.supplierInvoiceSku.errors.0' => 'Не более 100 символов'),
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
            /***********************************************************************************************
             * 'supplierInvoiceDate'
             ***********************************************************************************************/
            'valid supplierInvoiceDate 2013-03-16T12:34:56' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16T12:34:56'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:56+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16'),
                array("supplierInvoiceDate" => '2013-03-16T00:00:00+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16 12:34' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16 12:34'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:00+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16 12:34:45' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16 12:34:45'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:45+0400')
            ),
            'empty supplierInvoiceDate' => array(
                201,
                array('supplierInvoiceDate' => ''),
            ),
            'not valid supplierInvoiceDate 2013-02-31' => array(
                400,
                array('supplierInvoiceDate' => '2013-02-31'),
                array(
                    'children.supplierInvoiceDate.errors.0'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid supplierInvoiceDate aaa' => array(
                400,
                array('supplierInvoiceDate' => 'aaa'),
                array(
                    'children.supplierInvoiceDate.errors.0'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'valid supplierInvoiceDate is less than acceptanceDate' => array(
                201,
                array(
                    'supplierInvoiceDate' => '2013-03-14',
                    'acceptanceDate' => '2013-03-15'
                )
            ),
            'not valid supplierInvoiceDate is more than acceptanceDate' => array(
                400,
                array(
                    'supplierInvoiceDate' => '2013-03-15',
                    'acceptanceDate' => '2013-03-14'
                ),
                array(
                    'children.supplierInvoiceDate.errors.0'
                    =>
                    'Дата накладной не должна быть старше даты приемки',
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
}
