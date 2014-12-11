<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\InvoiceBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoiceProductControllerTest extends WebTestCase
{
    public function testGetProductInvoiceProducts()
    {
        $store = $this->factory()->store()->getStore();
        $product1 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 1%','purchasePrice' => 35.24, 'vat' => 10)
        );
        $product2 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 5%','purchasePrice' => 35.64, 'vat' => 10)
        );
        $product3 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 0%','purchasePrice' => 42.15, 'vat' => 10)
        );

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2013-10-18T09:39:47+0400'), $store->id)
                ->createInvoiceProduct($product1->id, 100, 36.70)
                ->createInvoiceProduct($product3->id, 20, 42.90)
            ->flush();

        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2013-10-18T12:22:00+0400'), $store->id)
                ->createInvoiceProduct($product1->id, 120, 37.20)
                ->createInvoiceProduct($product2->id, 200, 35.80)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product1->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($invoice1->products[1]->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($invoice2->products[1]->id, '*.id', $getResponse);
        Assert::assertJsonPathEquals($invoice1->products[0]->id, '1.id', $getResponse);
        Assert::assertJsonPathEquals($invoice2->products[0]->id, '0.id', $getResponse);
        Assert::assertJsonPathEquals($invoice1->id, '1.parent.id', $getResponse);
        Assert::assertJsonPathEquals($invoice2->id, '0.parent.id', $getResponse);
        Assert::assertNotJsonHasPath('*.store', $getResponse);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse);

        Assert::assertJsonHasPath('0.date', $getResponse);
        Assert::assertJsonHasPath('0.parent.date', $getResponse);
        $this->assertEquals($getResponse[0]['date'], $getResponse[0]['parent']['date']);

        Assert::assertJsonHasPath('1.date', $getResponse);
        Assert::assertJsonHasPath('1.parent.date', $getResponse);
        $this->assertEquals($getResponse[1]['date'], $getResponse[1]['parent']['date']);
    }

    public function testInvoiceProductTotalPriceWithFloatQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $product1 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 1%','purchasePrice' => 35.24, 'vat' => 10)
        );
        $product2 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 5%','purchasePrice' => 35.64, 'vat' => 10)
        );
        $product3 = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 0%','purchasePrice' => 42.15, 'vat' => 10)
        );

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2013-10-18T09:39:47+0400'), $store->id)
                ->createInvoiceProduct($product1->id, 99.99, 36.78)
                ->createInvoiceProduct($product2->id, 0.4, 21.77)
                ->createInvoiceProduct($product3->id, 7.77, 42.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product1->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(36.78, '*.price', $getResponse);
        Assert::assertJsonPathEquals(99.99, '*.quantity', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product2->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(0.4, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals(21.77, '*.price', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product3->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(42.99, '*.price', $getResponse);
        Assert::assertJsonPathEquals(7.77, '*.quantity', $getResponse);
    }

    public function testInvoiceProductWithVATFields()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->createProductByName(
            'Кефир 1%',
            null,
            array('purchasePrice' => 35.24, 'vat' => 10)
        );

        $this->factory()
            ->invoice()
            ->createInvoice(
                array(
                    'date' => '2013-10-18T09:39:47+0400',
                    'includesVAT' => true,
                ),
                $store->id
            )
            ->createInvoiceProduct($product->id, 99.99, 36.78)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '0.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(3343.66, '0.totalPriceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(333.97, '0.totalAmountVAT', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.priceEntered', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.price', $getResponse);
        Assert::assertJsonPathEquals(33.44, '0.priceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(3.34, '0.amountVAT', $getResponse);
        Assert::assertJsonPathEquals(99.99, '0.quantity', $getResponse);
    }

    /**
     * Проверяем что указав цену без НДС получим данные соответствующие данным теста выше
     */
    public function testInvoiceProductWithVATFieldsWithoutVAT()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->createProductByName(
            'Кефир 1%',
            null,
            array('purchasePrice' => 35.24, 'vat' => 10)
        );

        $this->factory()
            ->invoice()
            ->createInvoice(
                array(
                    'date' => '2013-10-18T09:39:47+0400',
                    'includesVAT' => false,
                ),
                $store->id
            )
            ->createInvoiceProduct($product->id, 99.99, 33.44)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '0.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(3343.66, '0.totalPriceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(333.97, '0.totalAmountVAT', $getResponse);
        Assert::assertJsonPathEquals(33.44, '0.priceEntered', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.price', $getResponse);
        Assert::assertJsonPathEquals(33.44, '0.priceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(3.34, '0.amountVAT', $getResponse);
        Assert::assertJsonPathEquals(99.99, '0.quantity', $getResponse);
    }

    public function testInvoiceProductVATFieldChangeIncludesVATInInvoice()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product = $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир 1%', 'purchasePrice' => 35.24, 'vat' => 10)
        );

        $invoiceData = InvoiceBuilder::create(null, null, $supplier->id)
            ->setIncludesVAT(true)
            ->addProduct($product->id, 99.99, 36.78);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/invoices",
            $invoiceData->toArray()
        );
        $this->assertResponseCode(201);
        $invoiceId = $invoiceResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '0.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(3343.66, '0.totalPriceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(333.97, '0.totalAmountVAT', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.priceEntered', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.price', $getResponse);
        Assert::assertJsonPathEquals(33.44, '0.priceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(3.34, '0.amountVAT', $getResponse);
        Assert::assertJsonPathEquals(99.99, '0.quantity', $getResponse);

        $invoiceData->setIncludesVAT(false);

        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData->toArray()
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(false, 'includesVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $invoiceResponse);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $invoiceResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(4045.60, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(367.96, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(40.46, "0.price", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.68, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);

        $invoiceData->setIncludesVAT(true);

        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/invoices/{$invoiceId}",
            $invoiceData->toArray()
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(true, 'includesVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $invoiceResponse);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $invoiceResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}/invoiceProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '0.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(3343.66, '0.totalPriceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(333.97, '0.totalAmountVAT', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.priceEntered', $getResponse);
        Assert::assertJsonPathEquals(36.78, '0.price', $getResponse);
        Assert::assertJsonPathEquals(33.44, '0.priceWithoutVAT', $getResponse);
        Assert::assertJsonPathEquals(3.34, '0.amountVAT', $getResponse);
        Assert::assertJsonPathEquals(99.99, '0.quantity', $getResponse);
    }
}
