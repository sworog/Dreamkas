<?php

namespace Lighthouse\CoreBundle\Tests\Meta;

use JMS\Serializer\Serializer;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Meta\MetaDocument;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class MetaDocumentTest extends WebTestCase
{
    public function testReferenceManyIsSerialized()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $storeId = $this->createStore();
        $manager = $this->factory->getDepartmentManager($storeId);
        $invoiceId = $this->createInvoice(array(), $storeId, $manager);
        $invoiceProductId1 = $this->createInvoiceProduct($invoiceId, $productId1, 10, 12.80, $storeId, $manager);
        $invoiceProductId2 = $this->createInvoiceProduct($invoiceId, $productId2, 5, 18.70, $storeId, $manager);

        /* @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice');
        /* @var Invoice $invoice */
        $invoice = $invoiceRepository->find($invoiceId);

        $metaDocument = new MetaDocument($invoice);
        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $jsonData = $serializer->serialize($metaDocument, 'json');
        $json = json_decode($jsonData, true);
        Assert::assertJsonHasPath('_meta', $json);
        Assert::assertJsonHasPath('products', $json);
        Assert::assertJsonPathCount(2, 'products.*.id', $json);
        Assert::assertJsonPathEquals($invoiceProductId1, 'products.*.id', $json, 1);
        Assert::assertJsonPathEquals($invoiceProductId2, 'products.*.id', $json, 1);
    }
}
