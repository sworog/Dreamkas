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
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\NumericFactory;
use DateTime;

class ProductTotalsTest extends ContainerAwareTestCase
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
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->getContainer()->get('lighthouse.core.types.numeric.factory');
    }

    public function testShopProductAmountChangesOnInOutOperations()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();

        $numericFactory = $this->getNumericFactory();

        $store = new Store();
        $store->number = '42';
        $store->address = '42';
        $store->contacts = '42';

        $invoice = new Invoice();
        $invoice->sku = 'sdfwfsf232';
        $invoice->supplier = 'ООО "Поставщик"';
        $invoice->acceptanceDate = new \DateTime();
        $invoice->accepter = 'Приемных Н.П.';
        $invoice->legalEntity = 'ООО "Магазин"';
        $invoice->supplierInvoiceSku = '1248373';
        $invoice->supplierInvoiceDate = new DateTime('-1');
        $invoice->store = $store;

        $product = new Product();
        $product->name = 'Кефир "Веселый Молочник" 1% 950гр';
        $product->units = 'gr';
        $product->barcode = '4607025392408';
        $product->purchasePrice = $numericFactory->createMoney('12.34');
        $product->sku = 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР';
        $product->vat = 10;
        $product->vendor = 'Вимм-Билль-Данн';
        $product->vendorCountry = 'Россия';
        $product->info = 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса';

        $versionFactory = $this->getContainer()->get('lighthouse.core.versionable.factory');
        $productVersion = $versionFactory->createDocumentVersion($product);

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->price = $numericFactory->createMoney('10.10');
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->product = $productVersion;
        $invoiceProduct->quantity = 10;

        $manager->persist($store);
        $manager->persist($product);
        $manager->persist($invoice);
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertInstanceOf(Invoice::getClassName(), $invoiceProduct->invoice);
        $this->assertEquals($invoiceProduct->invoice->id, $invoice->id);

        /* @var StoreProductRepository $storeProductRepository */
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProduct = $storeProductRepository->findByStoreIdProductId($store->id, $product->id);

        $this->assertEquals(10, $storeProduct->inventory);

        $invoiceProduct->quantity = 3;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(3, $storeProduct->inventory);

        $invoiceProduct->quantity = 4;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(4, $storeProduct->inventory);

        $manager->remove($invoiceProduct);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(0, $storeProduct->inventory);

        $invoiceProduct1 = new InvoiceProduct();
        $invoiceProduct1->price = $numericFactory->createMoney(11.11);
        $invoiceProduct1->invoice = $invoice;
        $invoiceProduct1->product = $productVersion;
        $invoiceProduct1->quantity = 10;

        $invoiceProduct2 = new InvoiceProduct();
        $invoiceProduct2->price = $numericFactory->createMoney(22.22);
        $invoiceProduct2->invoice = $invoice;
        $invoiceProduct2->product = $productVersion;
        $invoiceProduct2->quantity = 5;

        $manager->persist($invoiceProduct1);
        $manager->persist($invoiceProduct2);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(15, $storeProduct->inventory);
        $this->assertEquals(2222, $storeProduct->lastPurchasePrice->getCount());

        // Purchase
        $saleProduct = new SaleProduct();
        $saleProduct->product = $productVersion;
        $saleProduct->quantity = $numericFactory->createQuantity(5);
        $saleProduct->price = $numericFactory->createMoney(10.67);

        $sale = new Sale();
        $sale->hash = md5(uniqid('sale', true));
        $sale->store = $store;
        $sale->products = array($saleProduct);

        $manager->persist($sale);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(10, $storeProduct->inventory);

        $saleProduct2 = new SaleProduct();
        $saleProduct2->product = $productVersion;
        $saleProduct2->quantity = $numericFactory->createQuantity(12);
        $saleProduct2->price = $numericFactory->createMoney(10.67);

        $sale2 = new Sale();
        $sale2->hash = md5(uniqid('sale', true));
        $sale2->store = $store;
        $sale2->products = array($saleProduct2);

        $manager->persist($sale2);
        $manager->flush();

        $storeProductRepository->refresh($storeProduct);
        $this->assertEquals(-2, $storeProduct->inventory);
    }
}
