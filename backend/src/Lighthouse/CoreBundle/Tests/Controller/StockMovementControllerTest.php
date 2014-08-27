<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StockMovementControllerTest extends WebTestCase
{
    /**
     * @return array
     */
    protected function createStockMovements()
    {
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $invoice1 = $this->factory()
            ->invoice()
            ->createInvoice(array('date' => '2014-07-24 19:05:24'), $store1->id)
                ->createInvoiceProduct($productIds['1'], 10, 14.99)
                ->createInvoiceProduct($productIds['2'], 23.7, 13.59)
            ->flush();

        $invoice2 = $this->factory()
            ->invoice()
            ->createInvoice(array('date' => '2014-07-23 11:45:03'), $store2->id)
                ->createInvoiceProduct($productIds['1'], 1, 16)
                ->createInvoiceProduct($productIds['3'], 23.7, 13.59)
                ->createInvoiceProduct($productIds['2'], 10.001, 12.54)
            ->flush();

        $writeOff1 = $this->factory()
            ->writeOff()
            ->createWriteOff($store1, '2014-07-26 00:05:46')
                ->createWriteOffProduct($productIds['1'], 1, 14.95, 'Порча')
                ->createWriteOffProduct($productIds['2'], 0.05, 15.00, 'Бой')
            ->flush();

        $writeOff2 = $this->factory()
            ->writeOff()
            ->createWriteOff($store1, '2014-06-06 23:45:12')
                ->createWriteOffProduct($productIds['3'], 2.12, 10, 'Украли')
            ->flush();

        $stockIn1 = $this->factory()
            ->stockIn()
            ->createStockIn($store1, '2014-07-06 23:45:12')
                ->createStockInProduct($productIds['1'], 3, 13.76)
            ->flush();

        $stockIn2 = $this->factory()
            ->stockIn()
            ->createStockIn($store1, '2014-07-07 20:42:32')
                ->createStockInProduct($productIds['3'], 7, 12.6)
            ->flush();

        $supplierReturn1 = $this->factory()
            ->supplierReturn()
            ->createSupplierReturn($store1, '2014-05-07 23:45:12')
                ->createSupplierReturnProduct($productIds['1'], 2.12, 10)
            ->flush();

        $supplierReturn2 = $this->factory()
            ->supplierReturn()
            ->createSupplierReturn($store2, '2014-05-06 3:43:12')
                ->createSupplierReturnProduct($productIds['2'], 2.4, 17)
            ->flush();

        return array(
            'store1' => $store1->id,
            'store2' => $store2->id,
            'invoice1' => $invoice1->id,
            'invoice2' => $invoice2->id,
            'writeOff1' => $writeOff1->id,
            'writeOff2' => $writeOff2->id,
            'stockIn1' => $stockIn1->id,
            'stockIn2' => $stockIn2->id,
            'supplierReturn1' => $supplierReturn1->id,
            'supplierReturn2' => $supplierReturn2->id,
        );
    }

    public function testGetAction()
    {
        $ids = $this->createStockMovements();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stockMovements'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(8, '*.id', $response);

        Assert::assertJsonPathEquals('Invoice', '*.type', $response, 2);
        Assert::assertJsonPathEquals('WriteOff', '*.type', $response, 2);
        Assert::assertJsonPathEquals('StockIn', '*.type', $response, 2);

        Assert::assertJsonPathEquals($ids['store1'], '0.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '1.store.id', $response);
        Assert::assertJsonPathEquals($ids['store2'], '2.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '3.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '4.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '5.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '6.store.id', $response);
        Assert::assertJsonPathEquals($ids['store2'], '7.store.id', $response);

        Assert::assertJsonPathEquals($ids['writeOff1'], '0.id', $response);
        Assert::assertJsonPathEquals($ids['invoice1'], '1.id', $response);
        Assert::assertJsonPathEquals($ids['invoice2'], '2.id', $response);
        Assert::assertJsonPathEquals($ids['stockIn2'], '3.id', $response);
        Assert::assertJsonPathEquals($ids['stockIn1'], '4.id', $response);
        Assert::assertJsonPathEquals($ids['writeOff2'], '5.id', $response);
        Assert::assertJsonPathEquals($ids['supplierReturn1'], '6.id', $response);
        Assert::assertJsonPathEquals($ids['supplierReturn2'], '7.id', $response);
    }

    /**
     * @dataProvider filterProvider
     * @param array $query
     * @param array $expected
     */
    public function testFilter(array $query, array $expected)
    {
        $ids = $this->createStockMovements();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stockMovements',
            null,
            $query
        );

        $this->assertResponseCode(200);

        $expectedIds = array();
        foreach ($expected as $key) {
            $expectedIds[] = $ids[$key];
        }

        $responseIds = array_map(
            function ($item) {
                return $item['id'];
            },
            $response
        );

        $this->assertEquals($expectedIds, $responseIds);
    }

    /**
     * @return array
     */
    public function filterProvider()
    {
        return array(
            'types, dateFrom and dateTo' => array(
                array(
                    'types' => 'Invoice',
                    'dateFrom' => '2014-07-20',
                    'dateTo' => '2014-07-25'
                ),
                array('invoice1', 'invoice2')
            ),
            'types' => array(
                array(
                    'types' => 'Invoice,WriteOff',
                ),
                array('writeOff1', 'invoice1', 'invoice2', 'writeOff2')
            ),
            'types with spaces' => array(
                array(
                    'types' => 'Invoice ,  WriteOff',
                ),
                array('writeOff1', 'invoice1', 'invoice2', 'writeOff2')
            ),
            'dateTo' => array(
                array(
                    'dateTo' => '2014-07-01',
                ),
                array('writeOff2', 'supplierReturn1', 'supplierReturn2')
            ),
            'dateFrom' => array(
                array(
                    'dateFrom' => '2014-07-01',
                ),
                array('writeOff1', 'invoice1', 'invoice2', 'stockIn2', 'stockIn1')
            ),
            'custom dates format' => array(
                array(
                    'dateFrom' => '01.06.2014',
                    'dateTo' => 'Fri, 15 Jun 2014 18:39:05 +0400'
                ),
                array('writeOff2')
            ),
            'empty query' => array(
                array(),
                array(
                    'writeOff1',
                    'invoice1',
                    'invoice2',
                    'stockIn2',
                    'stockIn1',
                    'writeOff2',
                    'supplierReturn1',
                    'supplierReturn2'
                )
            )
        );
    }

    /**
     * @dataProvider filterValidationProvider
     * @param array $query
     * @param int $expectedResponseCode
     * @param array $assertions
     */
    public function testFilterValidation(array $query, $expectedResponseCode, array $assertions)
    {
        $this->createStockMovements();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->client->setCatchException();
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stockMovements',
            null,
            $query
        );

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function filterValidationProvider()
    {
        return array(
            'invalid dateFrom' => array(
                array(
                    'types' => 'Invoice',
                    'dateFrom' => 'aaa',
                    'dateTo' => '2014-07-25'
                ),
                400,
                array(
                    'errors.children.dateFrom.errors.0'
                    =>
                    'Вы ввели неверную дату aaa, формат должен быть следующий дд.мм.гггг чч:мм',
                    'errors.children.dateTo.errors.0' => null,
                )
            ),
            'invalid dateTo' => array(
                array(
                    'types' => 'Invoice',
                    'dateFrom' => '2014-07-21',
                    'dateTo' => 'aaa'
                ),
                400,
                array(
                    'errors.children.dateTo.errors.0'
                    =>
                    'Вы ввели неверную дату aaa, формат должен быть следующий дд.мм.гггг чч:мм',
                    'errors.children.dateFrom.errors.0' => null,
                )
            ),
            'valid extra field' => array(
                array(
                    'types' => 'Invoice,  WriteOff',
                    'extra' => 'dummy'
                ),
                200,
                array(
                    '*.type' => 'Invoice'
                )
            ),
            'types array' => array(
                array(
                    'types' => array('Invoice',  'WriteOff'),
                ),
                200,
                array(
                    '*.type' => 'Invoice'
                )
            ),
            'no types' => array(
                array(),
                200,
                array(
                    '*.type' => 'Invoice'
                )
            )
        );
    }
}
