<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Invoice;
use Lighthouse\CoreBundle\Document\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Money;

class InvoiceProductTest extends WebTestCase
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

        $invoice = new Invoice();
        $invoice->sku = 'sdfwfsf232';
        $invoice->supplier = 'ООО "Поставщик"';
        $invoice->acceptanceDate = new \DateTime();
        $invoice->accepter = 'Приемных Н.П.';
        $invoice->legalEntity = 'ООО "Магазин"';
        $invoice->supplierInvoiceSku = '1248373';
        $invoice->supplierInvoiceDate = new \DateTime('-1');

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

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->invoice = $invoice;
        $invoiceProduct->product = $product;
        $invoiceProduct->quantity = 10;

        $manager->persist($product);
        $manager->persist($invoice);
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertInstanceOf('\\Lighthouse\\CoreBundle\\Document\\Invoice', $invoiceProduct->invoice);
        $this->assertEquals($invoiceProduct->invoice->id, $invoice->id);

        /*
        $productRepo = $manager->getRepository('LighthouseCoreBundle:Product');

        $product = $productRepo->find($product->id);
        $this->assertInstanceOf('\\Lighthouse\\CoreBundle\\Document\\Product', $product);
        */
        $this->assertEquals(10, $product->amount);

        $invoiceProduct->quantity = 3;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertEquals(3, $product->amount);

        $invoiceProduct->quantity = 4;
        $manager->persist($invoiceProduct);
        $manager->flush();

        $this->assertEquals(4, $product->amount);

        $manager->remove($invoiceProduct);
        $manager->flush();

        $this->assertEquals(0, $product->amount);
    }
}
