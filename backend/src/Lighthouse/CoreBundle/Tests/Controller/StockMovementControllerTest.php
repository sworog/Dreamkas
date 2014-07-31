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

        return array(
            'store1' => $store1->id,
            'store2' => $store2->id,
            'invoice1' => $invoice1->id,
            'invoice2' => $invoice2->id,
            'writeOff1' => $writeOff1->id,
            'writeOff2' => $writeOff2->id
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

        Assert::assertJsonPathCount(4, '*.id', $response);

        Assert::assertJsonPathEquals('Invoice', '*.type', $response, 2);
        Assert::assertJsonPathEquals('WriteOff', '*.type', $response, 2);

        Assert::assertJsonPathEquals($ids['store1'], '0.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '1.store.id', $response);
        Assert::assertJsonPathEquals($ids['store2'], '2.store.id', $response);
        Assert::assertJsonPathEquals($ids['store1'], '3.store.id', $response);

        Assert::assertJsonPathEquals($ids['writeOff1'], '0.id', $response);
        Assert::assertJsonPathEquals($ids['invoice1'], '1.id', $response);
        Assert::assertJsonPathEquals($ids['invoice2'], '2.id', $response);
        Assert::assertJsonPathEquals($ids['writeOff2'], '3.id', $response);
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
            '/api/1/stockMovements?' . http_build_query($query)
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
                array('writeOff2')
            ),
            'dateFrom' => array(
                array(
                    'dateFrom' => '2014-07-01',
                ),
                array('writeOff1', 'invoice1', 'invoice2')
            )
        );
    }
}
