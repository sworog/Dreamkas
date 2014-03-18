<?php

namespace Lighthouse\CoreBundle\Tests\Meta;

use JMS\Serializer\SerializerInterface;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Meta\MetaDocument;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class MetaDocumentTest extends WebTestCase
{
    /**
     * @return SerializerInterface
     */
    protected function getSerializer()
    {
        return $this->getContainer()->get('serializer');
    }

    public function testInvoiceProductsReferenceManyIsSerialized()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $storeId = $this->factory->store()->getStore();
        $manager = $this->factory->store()->getDepartmentManager($storeId);
        $invoiceId = $this->createInvoice(array(), $storeId, $manager);
        $invoiceProductId1 = $this->createInvoiceProduct($invoiceId, $productId1, 10, 12.80, $storeId, $manager);
        $invoiceProductId2 = $this->createInvoiceProduct($invoiceId, $productId2, 5, 18.70, $storeId, $manager);

        /* @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice');
        /* @var Invoice $invoice */
        $invoice = $invoiceRepository->find($invoiceId);

        $metaDocument = new MetaDocument($invoice);
        $serializer = $this->getSerializer();
        $jsonData = $serializer->serialize($metaDocument, 'json');
        $json = json_decode($jsonData, true);
        Assert::assertJsonHasPath('_meta', $json);
        Assert::assertJsonHasPath('products', $json);
        Assert::assertJsonPathCount(2, 'products.*.id', $json);
        Assert::assertJsonPathEquals($invoiceProductId1, 'products.*.id', $json, 1);
        Assert::assertJsonPathEquals($invoiceProductId2, 'products.*.id', $json, 1);
    }

    public function testWriteOffProductsReferenceManyIsSerialized()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $storeId = $this->factory->store()->getStore();
        $manager = $this->factory->store()->getDepartmentManager($storeId);
        $writeOffId = $this->createWriteOff('431-789', null, $storeId, $manager);
        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId, $productId1, 10, 12.8, '*', $storeId, $manager);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId, $productId2, 5, 18.7, '**', $storeId, $manager);

        /* @var WriteOffRepository $writeOffRepository */
        $writeOffRepository = $this->getContainer()->get('lighthouse.core.document.repository.write_off');
        /* @var WriteOff $invoice */
        $writeOff = $writeOffRepository->find($writeOffId);

        $metaDocument = new MetaDocument($writeOff);
        $serializer = $this->getSerializer();
        $jsonData = $serializer->serialize($metaDocument, 'json');
        $json = json_decode($jsonData, true);
        Assert::assertJsonHasPath('_meta', $json);
        Assert::assertJsonHasPath('products', $json);
        Assert::assertJsonPathCount(2, 'products.*.id', $json);
        Assert::assertJsonPathEquals($writeOffProductId1, 'products.*.id', $json, 1);
        Assert::assertJsonPathEquals($writeOffProductId2, 'products.*.id', $json, 1);
    }
}
