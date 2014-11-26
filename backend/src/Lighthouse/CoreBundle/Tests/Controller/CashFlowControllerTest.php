<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CashFlowControllerTest extends WebTestCase
{
    public function testPostCashFlowAction()
    {
        $cashFlowData = array(
            'direction' => 'in',
            'date' => date('C'),
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
            'date' => date('C'),
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
                'date' => date('C', strtotime("-{$i}")),
                'amount' => 3344.22*$i,
                'comment' => 'Жизнь тлен ' . $i
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
            Assert::assertJsonPathEquals($cashFlows[$i], $i, $getResponse);
        }
    }
}
