<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Purchase\Purchase;
use Lighthouse\CoreBundle\Document\Purchase\Product\PurchaseProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\Money;

class InvoiceProductTest extends ContainerAwareTestCase
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

    public function testShopProductAmountChangesOnInvoiceProductOperations()
    {
        $this->clearMongoDb();

        $manager = $this->getManager();

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
        $invoice->supplierInvoiceDate = new \DateTime('-1');
        $invoice->store = $store;

        $product = new Product();
        $product->name = 'Кефир "Веселый Молочник" 1% 950гр';
        $product->units = 'gr';
        $product->barcode = '4607025392408';
        $product->purchasePrice = new Money(1234);
        $product->sku = 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР';
        $product->vat = 10;
        $product->vendor = 'Вимм-Билль-Данн';
        $product->vendorCountry = 'Россия';
        $product->info = 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса';

        $versionFactory = $this->getContainer()->get('lighthouse.core.versionable.factory');
        $productVersion = $versionFactory->createDocumentVersion($product);

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->price = new Money(1010);
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->product = $productVersion;
        $invoiceProduct->quantity = 10;

        $manager->persist($store);
        $manager->persist($product);
        $manager->persist($invoice);
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertInstanceOf('\\Lighthouse\\CoreBundle\\Document\\Invoice\\Invoice', $invoiceProduct->invoice);
        $this->assertEquals($invoiceProduct->invoice->id, $invoice->id);

        /* @var StoreProductRepository $storeProductRepository */
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProduct = $storeProductRepository->findByStoreIdProductId($store->id, $product->id);

        $this->assertEquals(10, $storeProduct->amount);

        $invoiceProduct->quantity = 3;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertEquals(3, $storeProduct->amount);

        $invoiceProduct->quantity = 4;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertEquals(4, $product->amount);

        $manager->remove($invoiceProduct);
        $manager->flush();

        $this->assertEquals(0, $product->amount);

        $invoiceProduct1 = new InvoiceProduct();
        $invoiceProduct1->price = new Money(1111);
        $invoiceProduct1->invoice = $invoice;
        $invoiceProduct1->product = $productVersion;
        $invoiceProduct1->quantity = 10;

        $invoiceProduct2 = new InvoiceProduct();
        $invoiceProduct2->price = new Money(2222);
        $invoiceProduct2->invoice = $invoice;
        $invoiceProduct2->product = $productVersion;
        $invoiceProduct2->quantity = 5;

        $manager->persist($invoiceProduct1);
        $manager->persist($invoiceProduct2);
        $manager->flush();

        $this->assertEquals(15, $product->amount);
        $this->assertEquals(2222, $product->lastPurchasePrice->getCount());

        // Purchase
        $purchaseProduct = new PurchaseProduct();
        $purchaseProduct->product = $product;
        $purchaseProduct->quantity = 5;
        $purchaseProduct->sellingPrice = new Money(1067);

        $purchase = new Purchase();
        $purchase->products = array($purchaseProduct);

        $manager->persist($purchase);
        $manager->flush();

        $this->assertEquals(10, $product->amount);

        $purchaseProduct2 = new PurchaseProduct();
        $purchaseProduct2->product = $product;
        $purchaseProduct2->quantity = 12;
        $purchaseProduct2->sellingPrice = new Money(1067);

        $purchase2 = new Purchase();
        $purchase2->products = array($purchaseProduct2);

        $manager->persist($purchase2);
        $manager->flush();

        $this->assertEquals(-2, $product->amount);
    }
}
