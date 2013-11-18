<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceCollection;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Versionable\VersionFactory;

class TrialBalanceTest extends ContainerAwareTestCase
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
     * @return VersionFactory
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

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';

        $invoiceData = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $product = $this->createProduct();

        /* @var StoreProductRepository $storeProductRepository */
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $invoice = new Invoice();
        $invoice->store = $store;
        $invoice->populate($invoiceData);

        $manager = $this->getManager();
        $manager->persist($invoice);
        $manager->flush();

        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        /** @var TrialBalanceCollection $startTrialBalance */
        $startTrialBalanceCursor = $trialBalanceRepository->findByStoreProduct($storeProduct->id);
        $startTrialBalance = new TrialBalanceCollection($startTrialBalanceCursor);

        $this->assertCount(0, $startTrialBalance);

        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->product = $productVersion;
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->price = new Money(99);
        $invoiceProduct->quantity = 9;

        $manager->persist($invoiceProduct);
        $manager->flush();


        /** @var TrialBalanceCollection $endTrialBalance */
        $endTrialBalanceCursor = $trialBalanceRepository->findByStoreProduct($storeProduct->id);
        $endTrialBalance = new TrialBalanceCollection($endTrialBalanceCursor);

        $this->assertCount(1, $endTrialBalance);

        /** @var TrialBalance $endTrialBalanceItem */
        $endTrialBalanceItem = $endTrialBalance->current();
        $this->assertEquals(9, $endTrialBalanceItem->quantity);
        $this->assertEquals(99, $endTrialBalanceItem->price->getCount());
        $this->assertEquals(891, $endTrialBalanceItem->totalPrice->getCount());

        $invoiceProduct->quantity = 10;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(10, $trialBalance->quantity);
        $this->assertEquals(99, $trialBalance->price->getCount());
        $this->assertEquals(990, $trialBalance->totalPrice->getCount());

        $manager->remove($invoiceProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);
        $this->assertTrue(null === $trialBalance);
    }

    public function testCreateTrialBalanceBySale()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();
        /** @var \Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $product = $this->createProduct();
        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';

        /* @var StoreProductRepository $storeProductRepository */
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $sale = new Sale();
        $sale->store = $store;

        $saleProduct = new SaleProduct();
        $saleProduct->sale = $sale;
        $saleProduct->product = $productVersion;
        $saleProduct->quantity = 3;
        $saleProduct->price = new Money(7999);

        $sale->products = array($saleProduct);

        $manager->persist($sale);
        $manager->persist($saleProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7999, $trialBalance->price->getCount());
        $this->assertEquals(3, $trialBalance->quantity);
        $this->assertEquals(23997, $trialBalance->totalPrice->getCount());
    }

    public function testCreateTrialBalanceByWriteOffCRUD()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();
        /* @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $product = $this->createProduct();
        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';

        /* @var StoreProductRepository $storeProductRepository */
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $writeOff = new WriteOff();
        $writeOff->store = $store;

        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;
        $writeOffProduct->product = $productVersion;
        $writeOffProduct->quantity = 3;
        $writeOffProduct->price = new Money(7999);
        $writeOffProduct->cause = 'Плохой товар';

        $writeOff->products = array($writeOffProduct);

        $manager->persist($writeOff);
        $manager->persist($writeOffProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7999, $trialBalance->price->getCount());
        $this->assertEquals(3, $trialBalance->quantity);
        $this->assertEquals(23997, $trialBalance->totalPrice->getCount());

        // Edit
        $writeOffProduct->price = new Money(7799);
        $writeOffProduct->quantity = 7;
        $manager->flush($writeOffProduct);

        $afterEditTrialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7799, $afterEditTrialBalance->price->getCount());
        $this->assertEquals(7, $afterEditTrialBalance->quantity);
        $this->assertEquals(54593, $afterEditTrialBalance->totalPrice->getCount());

        $this->assertEquals(7799, $trialBalance->price->getCount());
        $this->assertEquals(7, $trialBalance->quantity);
        $this->assertEquals(54593, $trialBalance->totalPrice->getCount());

        // Delete
        $manager->remove($writeOffProduct);
        $manager->flush();

        $afterDeleteTrialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);
        $this->assertTrue(null === $afterDeleteTrialBalance);
    }

    /**
     * @return Product
     */
    protected function createProduct()
    {
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

        $product = new Product();
        $product->populate($productData);

        $manager = $this->getManager();
        $manager->persist($product);
        $manager->flush();

        return $product;
    }
}
