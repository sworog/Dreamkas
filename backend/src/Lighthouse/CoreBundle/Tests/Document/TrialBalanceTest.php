<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Purchase\Purchase;
use Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceCollection;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
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

    /**
     * @return \Lighthouse\CoreBundle\Versionable\VersionFactory
     */
    protected function getVersionFactory()
    {
        return $this->getContainer()->get('lighthouse.core.versionable.factory');
    }

    public function testConstruct()
    {
        $trialBalance = new TrialBalance();
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\TrialBalance\\TrialBalance', $trialBalance);
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

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $trialBalance->$key);
        }
    }

    public function trialBalanceDataProvider()
    {
        return array(
            'trialBalance data' => array(
                array(
                    'beginningBalance' => 35,
                    'endingBalance' => 43,
                    'quantity' => 8,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'totalPrice' => 800,
                    'price' => 100
                )
            ),
            'trialBalance data expenditure' => array(
                array(
                    'beginningBalance' => 55,
                    'endingBalance' => 52,
                    'quantity' => -3,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'totalPrice' => 800,
                    'price' => 100
                )
            )
        );
    }

    public function testCreateTrialBalanceByInvoiceProductCRUD()
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

        /** @var \Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        /** @var TrialBalanceCollection $startTrialBalance */
        $startTrialBalanceCursor = $trialBalanceRepository->findByProduct($product->id);
        $startTrialBalance = new TrialBalanceCollection($startTrialBalanceCursor);

        $this->assertCount(0, $startTrialBalance);

        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->product = $productVersion;
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->price = new Money(99.99);
        $invoiceProduct->quantity = 9;

        $manager->persist($invoiceProduct);
        $manager->flush();


        /** @var TrialBalanceCollection $endTrialBalance */
        $endTrialBalanceCursor = $trialBalanceRepository->findByProduct($product->id);
        $endTrialBalance = new TrialBalanceCollection($endTrialBalanceCursor);

        $this->assertCount(1, $endTrialBalance);

        /** @var \Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance $endTrialBalance */
        $endTrialBalance = $endTrialBalance->current();
        $this->assertEquals(9, $endTrialBalance->quantity);
        $this->assertEquals(899.91, $endTrialBalance->totalPrice->getCount());
        $this->assertEquals(99.99, $endTrialBalance->price->getCount());

        $invoiceProduct->quantity = 10;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByProduct($product);

        $this->assertEquals(10, $trialBalance->quantity);
        $this->assertEquals(999.9, $trialBalance->totalPrice->getCount());
        $this->assertEquals(99.99, $trialBalance->price->getCount());

        $manager->remove($invoiceProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByProduct($product);
        $this->assertTrue(null === $trialBalance);
    }

    public function testCreateTrialBalanceByPurchase()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();
        /** @var \Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        /** @var \Lighthouse\CoreBundle\Document\Product\ProductRepository $productRepository */
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');

        $productId = $this->createProduct();
        $product = $productRepository->findOneBy(array('id' => $productId));

        $purchase = new Purchase();

        $purchaseProduct = new PurchaseProduct();
        $purchaseProduct->purchase = $purchase;
        $purchaseProduct->product = $product;
        $purchaseProduct->quantity = 3;
        $purchaseProduct->sellingPrice = new Money(79.99);

        $purchase->products = array($purchaseProduct);

        $manager->persist($purchase);
        $manager->persist($purchaseProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByProduct($product);

        $this->assertEquals(79.99, $trialBalance->price->getCount());
        $this->assertEquals(-3, $trialBalance->quantity);
        $this->assertEquals(239.97, $trialBalance->totalPrice->getCount());
    }

    public function testCreateTrialBalanceByWriteOffCRUD()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');

        $productId = $this->createProduct();
        $product = $productRepository->findOneBy(array('id' => $productId));

        $writeOff = new WriteOff();

        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;
        $writeOffProduct->product = $product;
        $writeOffProduct->quantity = 3;
        $writeOffProduct->price = new Money(79.99);
        $writeOffProduct->cause = 'Плохой товар';

        $writeOff->products = array($writeOffProduct);

        $manager->persist($writeOff);
        $manager->persist($writeOffProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByProduct($product);

        $this->assertEquals(79.99, $trialBalance->price->getCount());
        $this->assertEquals(-3, $trialBalance->quantity);
        $this->assertEquals(239.97, $trialBalance->totalPrice->getCount());

        // Edit
        $writeOffProduct->price = new Money(77.99);
        $writeOffProduct->quantity = 7;
        $manager->flush($writeOffProduct);

        $afterEditTrialBalance = $trialBalanceRepository->findOneByProduct($product);

        $this->assertEquals(77.99, $trialBalance->price->getCount());
        $this->assertEquals(-7, $trialBalance->quantity);
        $this->assertEquals(545.93, $trialBalance->totalPrice->getCount());

        // Delete
        $manager->remove($writeOffProduct);
        $manager->flush();

        $afterDeleteTrialBalance = $trialBalanceRepository->findOneByProduct($product);
        $this->assertTrue(null === $afterDeleteTrialBalance);
    }
}
