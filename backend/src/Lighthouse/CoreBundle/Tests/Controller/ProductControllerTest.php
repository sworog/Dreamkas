<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $postArray = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );
        $crawler = $client->request(
            'POST',
            'api/1/product',
            $postArray,
            array(),
            array(
                'HTTP_ACCEPT' => 'application/xml',
            )
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testCorsHeader()
    {
        $client = static::createClient();

        $postArray = array(
            'name' => 'Кефир',
        );

        $headers = array(
            'HTTP_Origin' => 'www.a.com',
        );

        $client->request('POST', 'api/1/product', $postArray, array(), $headers);

        /* @var $response Response */
        $response = $client->getResponse();
        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertEquals("www.a.com", $response->headers->get('Access-Control-Allow-Origin'));

        $client->request('POST', 'api/1/product', $postArray);
        /* @var $response Response */
        $response = $client->getResponse();
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }
}
