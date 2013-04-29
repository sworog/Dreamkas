<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Invoice;
use Lighthouse\CoreBundle\Document\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Document\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalanceCollection;
use Lighthouse\CoreBundle\Document\TrialBalanceRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Money;

class TrialBalanceTest extends WebTestCase
{
    /**
     * @return ManagerRegistry
     */
    protected function getManagerRegistry()
    {
        /* @var ManagerRegistry $odm */
        $odm = $this->getContainer()->get('doctrine_mongodb');
        return $odm;
    }

    /**
     * @return DocumentManager
     */
    protected function getManager()
    {
        return $this->getManagerRegistry()->getManager();
    }

    public function testConstruct()
    {
        $trialBalance = new TrialBalance();
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\TrialBalance', $trialBalance);
    }

    /**
     * @dataProvider trialBalanceDataProvider
     */
    public function testGetSetProperties(array $trialBalanceData)
    {
        $trialBalance = new TrialBalance();

        foreach ($trialBalanceData as $key => $value) {
            $trialBalance->$key = $value;
            $this->assertEquals($value, $trialBalance->$key);
        }

        $this->assertNull($trialBalance->id);
    }

    /**
     * @dataProvider trialBalanceDataProvider
     */
    public function testPopulateAndToArray(array $data)
    {
        $trialBalance = new TrialBalance();
        $trialBalance->populate($data);

        $trialBalanceArray = $trialBalance->toArray();
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $trialBalanceArray[$key]);
        }
    }

    public function trialBalanceDataProvider()
    {
        return array(
            'trialBalance data' => array(
                array(
                    'beginningBalance' => 35,
                    'endingBalance' => 43,
                    'receipts' => 8,
                    'expenditure' => 0,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'receiptsMoney' => 800,
                    'expenditureMoney' => 0,
                    'unitValue' => 100
                )
            ),
            'trialBalance data expenditure' => array(
                array(
                    'beginningBalance' => 55,
                    'endingBalance' => 52,
                    'receipts' => 0,
                    'expenditure' => 3,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'receiptsMoney' => 800,
                    'expenditureMoney' => 0,
                    'unitValue' => 100
                )
            )
        );
    }

    public function testCreateTrialBalanceByInvoiceProductCreate()
    {
        $this->clearMongoDb();

        $invoiceData = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => new Money(30.48),
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $manager = $this->getManager();

        $product = new Product();
        $product->populate($productData);

        $invoice = new Invoice();
        $invoice->populate($invoiceData);

        $manager->persist($product);
        $manager->persist($invoice);
        $manager->flush();

        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        /** @var TrialBalanceCollection $startTrialBalance */
        $startTrialBalanceCursor = $trialBalanceRepository->findByProduct($product);
        $startTrialBalance = new TrialBalanceCollection($startTrialBalanceCursor);

        $this->assertCount(0, $startTrialBalance);


        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->product = $product;
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->price = new Money(99.99);
        $invoiceProduct->quantity = 9;

        $manager->persist($invoiceProduct);
        $manager->flush();


        /** @var TrialBalanceCollection $endTrialBalance */
        $endTrialBalanceCursor = $trialBalanceRepository->findByProduct($product);
        $endTrialBalance = new TrialBalanceCollection($endTrialBalanceCursor);

        $this->assertCount(1, $endTrialBalance);

        /** @var TrialBalance $endTrialBalance */
        $endTrialBalance = $endTrialBalance->current();
        $this->assertEquals(9, $endTrialBalance->quantity);
        $this->assertEquals(899.91, $endTrialBalance->totalPrice);
        $this->assertEquals(0, $endTrialBalance->beginningBalance);
        $this->assertEquals(0, $endTrialBalance->beginningBalanceMoney);
        $this->assertEquals(9, $endTrialBalance->endingBalance);
        $this->assertEquals(899.91, $endTrialBalance->endingBalanceMoney);
        $this->assertEquals(99.99, $endTrialBalance->price);

    }
}
