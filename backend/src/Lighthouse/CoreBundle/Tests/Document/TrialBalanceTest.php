<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
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
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
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

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->getContainer()->get('lighthouse.core.types.numeric.factory');
    }

    /**
     * @return TrialBalanceRepository
     */
    protected function getTrialBalanceRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
    }

    /**
     * @return StoreProductRepository
     */
    protected function getStoreProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.store_product');
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

        $manager = $this->getManager();

        $store = $this->factory()->store()->getStore('42');

        $this->authenticateProject();
        $product = $this->createProduct();

        $storeProductRepository = $this->getStoreProductRepository();
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $trialBalanceRepository = $this->getTrialBalanceRepository();

        $startTrialBalanceCursor = $trialBalanceRepository->findByStoreProduct($storeProduct->id);
        $startTrialBalance = new TrialBalanceCollection($startTrialBalanceCursor);

        $this->assertCount(0, $startTrialBalance);

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 9, 0.99)
            ->flush();

        // get invoice from right container
        $invoice = $this->getContainer()->get('lighthouse.core.document.repository.invoice')->find($invoice->id);

        $endTrialBalanceCursor = $trialBalanceRepository->findByStoreProduct($storeProduct->id);
        $endTrialBalance = new TrialBalanceCollection($endTrialBalanceCursor);

        $this->assertCount(1, $endTrialBalance);

        /** @var TrialBalance $endTrialBalanceItem */
        $endTrialBalanceItem = $endTrialBalance->current();
        $this->assertEquals(9000, $endTrialBalanceItem->quantity->getCount());
        $this->assertEquals(99, $endTrialBalanceItem->price->getCount());
        $this->assertEquals(891, $endTrialBalanceItem->totalPrice->getCount());

        $invoice->products[0]->quantity = $invoice->products[0]->quantity->set(10);
        $manager->persist($invoice);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(10000, $trialBalance->quantity->getCount());
        $this->assertEquals(99, $trialBalance->price->getCount());
        $this->assertEquals(990, $trialBalance->totalPrice->getCount());

        $manager->remove($invoice->products->remove(0));
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);
        $this->assertTrue(null === $trialBalance);
    }

    public function testCreateTrialBalanceBySale()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $manager = $this->getManager();
        $numericFactory = $this->getNumericFactory();

        $trialBalanceRepository = $this->getTrialBalanceRepository();

        $product = $this->createProduct();
        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';
        $manager->persist($store);
        $manager->flush();

        $storeProductRepository = $this->getStoreProductRepository();
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $sale = new Sale();
        $sale->store = $store;

        $saleProduct = new SaleProduct();
        $saleProduct->sale = $sale;
        $saleProduct->product = $productVersion;
        $saleProduct->quantity = $numericFactory->createQuantity(3);
        $saleProduct->price = $numericFactory->createMoney(79.99);

        $sale->products = array($saleProduct);

        $manager->persist($sale);
        $manager->persist($saleProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7999, $trialBalance->price->getCount());
        $this->assertEquals(3000, $trialBalance->quantity->getCount());
        $this->assertEquals(23997, $trialBalance->totalPrice->getCount());
    }

    public function testCreateTrialBalanceByWriteOffCRUD()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $manager = $this->getManager();
        $trialBalanceRepository = $this->getTrialBalanceRepository();

        $product = $this->createProduct();
        $productVersion = $this->getVersionFactory()->createDocumentVersion($product);

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';
        $manager->persist($store);
        $manager->flush();

        $numericFactory = $this->getNumericFactory();
        $storeProductRepository = $this->getStoreProductRepository();
        $storeProduct = $storeProductRepository->findOrCreateByStoreProduct($store, $product);

        $writeOff = new WriteOff();
        $writeOff->date = new \DateTime();
        $writeOff->store = $store;

        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->writeOff = $writeOff;
        $writeOffProduct->product = $productVersion;
        $writeOffProduct->quantity = $numericFactory->createQuantity(3);
        $writeOffProduct->price = $numericFactory->createMoney(79.99);
        $writeOffProduct->cause = 'Плохой товар';

        $writeOff->products = array($writeOffProduct);

        $manager->persist($writeOff);
        $manager->persist($writeOffProduct);
        $manager->flush();

        $trialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7999, $trialBalance->price->getCount());
        $this->assertEquals(3000, $trialBalance->quantity->getCount());
        $this->assertEquals(23997, $trialBalance->totalPrice->getCount());

        // Edit
        $writeOffProduct->price = $numericFactory->createMoney(77.99);
        $writeOffProduct->quantity = $numericFactory->createQuantity(7);
        $manager->flush($writeOffProduct);

        $afterEditTrialBalance = $trialBalanceRepository->findOneByStoreProduct($storeProduct);

        $this->assertEquals(7799, $afterEditTrialBalance->price->getCount());
        $this->assertEquals(7000, $afterEditTrialBalance->quantity->getCount());
        $this->assertEquals(54593, $afterEditTrialBalance->totalPrice->getCount());

        $this->assertEquals(7799, $trialBalance->price->getCount());
        $this->assertEquals(7000, $trialBalance->quantity->getCount());
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
