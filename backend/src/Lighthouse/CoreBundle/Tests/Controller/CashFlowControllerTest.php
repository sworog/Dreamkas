<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use DateTime;

class CashFlowControllerTest extends WebTestCase
{
    /**
     * @return array
     */
    protected function createCashFlows()
    {
        $cashFlow1 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'in',
                    300,
                    '2014-07-24 19:05:24'
                );
        $cashFlow2 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'in',
                    200,
                    '2014-07-23 11:45:03'
                );
        $cashFlow3 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'out',
                    400,
                    '2014-07-26 00:05:46'
                );
        $cashFlow4 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'in',
                    500,
                    '2014-06-06 23:45:12'
                );
        $cashFlow5 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'out',
                    600,
                    '2014-07-06 23:45:12'
                );
        $cashFlow6 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'out',
                    300,
                    '2014-07-07 20:42:32'
                );
        $cashFlow7 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'out',
                    700,
                    '2014-05-07 23:45:12'
                );
        $cashFlow8 = $this->factory()
            ->cashFlow()
                ->createCashFlow(
                    'in',
                    1000,
                    '2014-05-06 3:43:12'
                );

        return array(
            'cashFlow1' => $cashFlow1->id,
            'cashFlow2' => $cashFlow2->id,
            'cashFlow3' => $cashFlow3->id,
            'cashFlow4' => $cashFlow4->id,
            'cashFlow5' => $cashFlow5->id,
            'cashFlow6' => $cashFlow6->id,
            'cashFlow7' => $cashFlow7->id,
            'cashFlow8' => $cashFlow8->id,
        );
    }

    public function testPostCashFlowAction()
    {
        $cashFlowData = array(
            'direction' => 'in',
            'date' => date('Y-m-d\Th:i:sO'),
            'amount' => 3344.22,
            'comment' => 'Жизнь тлен'
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/cashFlows',
            $cashFlowData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($cashFlowData['direction'], 'direction', $response);
        Assert::assertJsonPathEquals($cashFlowData['date'], 'date', $response);
        Assert::assertJsonPathEquals($cashFlowData['amount'], 'amount', $response);
        Assert::assertJsonPathEquals($cashFlowData['comment'], 'comment', $response);
    }

    public function testGetCashFlowAction()
    {
        $cashFlowData = array(
            'direction' => 'in',
            'date' => date('Y-m-d\Th:i:sO'),
            'amount' => 3344.22,
            'comment' => 'Жизнь тлен'
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/cashFlows',
            $cashFlowData
        );

        $this->assertResponseCode(201);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/cashFlows/{$postResponse['id']}"
        );

        $this->assertResponseCode(200);


        Assert::assertJsonPathEquals($postResponse['id'], 'id', $getResponse);
        Assert::assertJsonPathEquals($cashFlowData['direction'], 'direction', $getResponse);
        Assert::assertJsonPathEquals($cashFlowData['date'], 'date', $getResponse);
        Assert::assertJsonPathEquals($cashFlowData['amount'], 'amount', $getResponse);
        Assert::assertJsonPathEquals($cashFlowData['comment'], 'comment', $getResponse);
    }

    public function testGetCashFlowsAction()
    {
        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $cashFlows = array();

        for ($i = 0; $i < 5; $i++) {
            $cashFlows[$i] = array(
                'direction' => $i%2?'in':'out',
                'date' => date('Y-m-d\Th:i:sO', strtotime("-{$i}")),
                'amount' => 3344*($i+1),
                'comment' => 'Comment for cash flow item ' . $i
            );

            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/cashFlows',
                $cashFlows[$i]
            );

            $this->assertResponseCode(201);

            $cashFlows[$i]['id'] = $postResponse['id'];
        }

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(5, '*.id', $getResponse);
        for ($i = 0; $i < 5; $i++) {
            Assert::assertJsonPathEquals($cashFlows[$i]['id'], '*.id', $getResponse);
            Assert::assertJsonPathEquals($cashFlows[$i]['direction'], '*.direction', $getResponse);
            Assert::assertJsonPathEquals($cashFlows[$i]['comment'], '*.comment', $getResponse);
            Assert::assertJsonPathEquals($cashFlows[$i]['amount'], '*.amount', $getResponse);
            Assert::assertJsonPathEquals($cashFlows[$i]['date'], '*.date', $getResponse);
        }
    }

    /**
     * @dataProvider validateProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostCashFlowValidation($expectedCode, array $data, array $assertions = array())
    {
        $cashFlowData = $data + array(
            'direction' => 'in',
            'date' => date('Y-m-d\Th:i:sO'),
            'amount' => 3344.22,
            'comment' => 'Жизнь тлен'
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/cashFlows',
            $cashFlowData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'direction'
             ***********************************************************************************************/
            'valid direction in' => array(
                201,
                array('direction' => 'in')
            ),
            'valid direction out' => array(
                201,
                array('direction' => 'out')
            ),
            'not valid direction' => array(
                400,
                array('direction' => 'notValid'),
                array(
                    'errors.children.direction.errors.0'
                    =>
                    'Выбранное Вами значение недопустимо.'
                )
            ),
            
            /***********************************************************************************************
             * 'date'
             ***********************************************************************************************/
            'valid date 2013-03-26T12:34:56' => array(
                201,
                array('date' => '2013-03-26T12:34:56'),
                array('date' => '2013-03-26T12:34:56+0400')
            ),
            'valid date 2013-03-26' => array(
                201,
                array('date' => '2013-03-26'),
                array('date' => '2013-03-26T00:00:00+0400')
            ),
            'valid date 2013-03-26 12:34' => array(
                201,
                array('date' => '2013-03-26 12:34'),
                array('date' => '2013-03-26T12:34:00+0400')
            ),
            'valid date 2013-03-26 12:34:45' => array(
                201,
                array('date' => '2013-03-26 12:34:45'),
                array('date' => '2013-03-26T12:34:45+0400')
            ),
            'empty date' => array(
                400,
                array('date' => ''),
                array('errors.children.date.errors.0' => 'Заполните это поле'),
            ),
            'not valid date 2013-02-31' => array(
                400,
                array('date' => '2013-02-31'),
                array(
                    'errors.children.date.errors.0'
                    =>
                    'Вы ввели неверную дату 2013-02-31, формат должен быть следующий дд.мм.гггг чч:мм'),
            ),
            'not valid date aaa' => array(
                400,
                array('date' => 'aaa'),
                array(
                    'errors.children.date.errors.0'
                    =>
                    'Вы ввели неверную дату aaa, формат должен быть следующий дд.мм.гггг чч:мм',),
            ),
            'not valid date __.__.____ __:__' => array(
                400,
                array('date' => '__.__.____ __:__'),
                array(
                    'errors.children.date.errors.0'
                    =>
                    'Вы ввели неверную дату __.__.____ __:__, формат должен быть следующий дд.мм.гггг чч:мм',),
            ),
            
            /***********************************************************************************************
             * 'amount'
             ***********************************************************************************************/
            'valid amount dot' => array(
                201,
                array('amount' => 10.89),
            ),
            'valid amount dot 79.99' => array(
                201,
                array('amount' => 79.99),
            ),
            'valid amount coma' => array(
                201,
                array('amount' => '10,89'),
            ),
            'not valid empty amount' => array(
                400,
                array('amount' => '',),
                array(
                    'errors.children.amount.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
            'not valid amount very float' => array(
                400,
                array('amount' => '10,898'),
                array(
                    'errors.children.amount.errors.0'
                    =>
                        'Сумма не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid amount very float dot' => array(
                400,
                array('amount' => '10.898'),
                array(
                    'errors.children.amount.errors.0'
                    =>
                        'Сумма не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid amount very float with dot' => array(
                201,
                array('amount' => '10.12')
            ),
            'not valid amount not a number' => array(
                400,
                array('amount' => 'not a number'),
                array(
                    'errors.children.amount.errors.0' => 'Значение должно быть числом',
                ),
            ),
            'not valid amount zero' => array(
                400,
                array('amount' => 0),
                array(
                    'errors.children.amount.errors.0' => 'Сумма не должна быть меньше или равна нулю'
                ),
            ),
            'not valid amount negative' => array(
                400,
                array('amount' => -10),
                array(
                    'errors.children.amount.errors.0' => 'Сумма не должна быть меньше или равна нулю'
                )
            ),
            'not valid amount too big 2 000 000 001' => array(
                400,
                array('amount' => 2000000001),
                array(
                    'errors.children.amount.errors.0' => 'Сумма не должна быть больше 10000000'
                ),
            ),
            'not valid amount too big 100 000 000' => array(
                400,
                array('amount' => '100000000'),
                array(
                    'errors.children.amount.errors.0' => 'Сумма не должна быть больше 10000000'
                ),
            ),
            'valid amount too big 10 000 000' => array(
                201,
                array('amount' => '10000000'),
            ),
            'not valid amount too big 10 000 001' => array(
                400,
                array('amount' => '10000001'),
                array(
                    'errors.children.amount.errors.0' => 'Сумма не должна быть больше 10000000'
                ),
            ),
            
            /***********************************************************************************************
             * 'comment'
             ***********************************************************************************************/
            'valid comment' => array(
                201,
                array('comment' => 'test'),
            ),
            'valid comment 100 chars' => array(
                201,
                array('comment' => str_repeat('z', 100)),
            ),
            'empty comment' => array(
                201,
                array('comment' => ''),
            ),
            'not valid comment too long' => array(
                400,
                array('comment' => str_repeat("z", 101)),
                array(
                    'errors.children.comment.errors.0'
                    =>
                        'Не более 100 символов',
                ),
            ),
        );
    }

    /**
     * @dataProvider filterProvider
     * @param array $query
     * @param array $expected
     */
    public function testFilter(array $query, array $expected)
    {
        $ids = $this->createCashFlows();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows',
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
                    'dateFrom' => '2014-07-20',
                    'dateTo' => '2014-07-25'
                ),
                array('cashFlow1', 'cashFlow2')
            ),
            'dateTo' => array(
                array(
                    'dateTo' => '2014-07-01',
                ),
                array('cashFlow4', 'cashFlow7', 'cashFlow8')
            ),
            'dateFrom' => array(
                array(
                    'dateFrom' => '2014-07-01',
                ),
                array('cashFlow3', 'cashFlow1', 'cashFlow2', 'cashFlow6', 'cashFlow5')
            ),
            'custom dates format' => array(
                array(
                    'dateFrom' => '01.06.2014',
                    'dateTo' => 'Fri, 15 Jun 2014 18:39:05 +0400'
                ),
                array('cashFlow4')
            ),
            'empty query' => array(
                array(),
                array(
                    'cashFlow3',
                    'cashFlow1',
                    'cashFlow2',
                    'cashFlow6',
                    'cashFlow5',
                    'cashFlow4',
                    'cashFlow7',
                    'cashFlow8'
                )
            )
        );
    }

    public function testDeleteCashFlowAction()
    {
        $cashFlow = $this->factory()->cashFlow()->createCashFlow();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/cashFlows/{$cashFlow->id}"
        );

        $this->assertResponseCode(204);

        $this->assertEmpty($deleteResponse);

        $this->client->setCatchException(true);
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/cashFlows/{$cashFlow->id}"
        );

        $this->assertResponseCode(404);
    }

    public function testAutoCreatedCashFlowEdit()
    {
        $product = $this->factory()->catalog()->getProduct();

        $this->factory()
            ->supplierReturn()
                ->createSupplierReturn(null, null, null, true)
                ->createSupplierReturnProduct($product->id, 100, 7)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows'
        );

        $this->assertResponseCode(200);

        $cashFlow = $getResponse[0];
        $cashFlowData = $cashFlow;
        unset($cashFlowData['id'], $cashFlowData['reason']);
        $cashFlowData['amount'] = 3333.3;

        $this->client->setCatchException(true);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/cashFlows/{$cashFlow['id']}",
            $cashFlowData
        );

        $this->assertResponseCode(409);

        Assert::assertJsonPathEquals(
            'Невозможно изменить/удалить автоматически созданную запись',
            'message',
            $deleteResponse
        );
    }

    public function testAutoCreatedCashFlowDelete()
    {
        $product = $this->factory()->catalog()->getProduct();

        $this->factory()
            ->supplierReturn()
                ->createSupplierReturn(null, null, null, true)
                ->createSupplierReturnProduct($product->id, 100, 7)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows'
        );

        $this->assertResponseCode(200);

        $cashFlow = $getResponse[0];

        $this->client->setCatchException(true);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/cashFlows/{$cashFlow['id']}"
        );

        $this->assertResponseCode(409);

        Assert::assertJsonPathEquals(
            'Невозможно изменить/удалить автоматически созданную запись',
            'message',
            $deleteResponse
        );
    }

    public function testAutoCreatedCashFlowGetForInvoice()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-11-31 00:00:00'), $store->id)
                ->createInvoiceProduct($product->id, 11, 5.78)
            ->flush();
        $expectedInvoiceDateTime = date('Y-m-d\T00:00:00O', strtotime('2014-11-31 00:00:00'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows',
            null,
            array('dateFrom' => '2014-11-01', 'dateTo' => '2014-12-31')
        );

        $this->assertResponseCode(200);

        $this->assertCount(0, $response);

        $expectedPaidDateTime = date('Y-m-d\T00:00:00O');
        $this->factory()
            ->invoice()
                ->editInvoice($invoice->id, array('paid' => true))
            ->flush();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows',
            null,
            array('dateFrom' => '2014-11-01', 'dateTo' => '2014-12-31')
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $response);
        Assert::assertJsonPathEquals($expectedPaidDateTime, '0.date', $response);
        Assert::assertJsonPathEquals('Invoice', '0.type', $response);
        Assert::assertJsonPathEquals($expectedInvoiceDateTime, '0.reasonDate', $response);
    }

    public function testAutoCreatedCashFlowGetForSalesByDay()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-2 month'), $store->id)
                ->createInvoiceProduct($product->id, 11, 5.78)
            ->flush();

        $sale = $this->factory()
            ->receipt()
                ->createSale(null, '-2 day')
                ->createReceiptProduct($product->id, 7, 10)
            ->flush();

        $return = $this->factory()
            ->receipt()
                ->createReturn(null, '-1 day', $sale)
                ->createReceiptProduct($product->id, 7, 10)
            ->flush();

        $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $expectedSalesDateTime = date('Y-m-d\T23:59:59O', strtotime('-2 day'));
        $expectedSalesReasonDateTime = date('Y-m-d\T00:00:00O', strtotime('-2 day'));
        $expectedReturnsDateTime = date('Y-m-d\T23:59:59O', strtotime('-1 day'));
        $expectedReturnsReasonDateTime = date('Y-m-d\T00:00:00O', strtotime('-1 day'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/cashFlows',
            null,
            array('dateFrom' => date('Y-m-d', strtotime('-3 day')), 'dateTo' => date('Y-m-d', strtotime('+3 day')))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);

        Assert::assertJsonPathEquals($expectedReturnsDateTime, '0.date', $response);
        Assert::assertJsonPathEquals($expectedReturnsReasonDateTime, '0.reasonDate', $response);
        Assert::assertJsonPathEquals('Returns', '0.type', $response);

        Assert::assertJsonPathEquals($expectedSalesDateTime, '1.date', $response);
        Assert::assertJsonPathEquals($expectedSalesReasonDateTime, '1.reasonDate', $response);
        Assert::assertJsonPathEquals('Sales', '1.type', $response);

    }
}
