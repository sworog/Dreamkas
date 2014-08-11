<?php

namespace Lighthouse\CoreBundle\Tests\Meta;

use JMS\Serializer\SerializerInterface;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository;
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

    /**
     * @return WriteOffRepository
     */
    protected function getWriteOffRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.stock_movement.writeoff');
    }

    public function testInvoiceProductsReferenceManyIsSerialized()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $store = $this->factory()->store()->getStore();
        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId1, 10, 12.80)
                ->createInvoiceProduct($productId2, 15, 18.70)
            ->flush();

        $metaDocument = new MetaDocument($invoice);
        $serializer = $this->getSerializer();
        $jsonData = $serializer->serialize($metaDocument, 'json');
        $json = json_decode($jsonData, true);
        Assert::assertJsonHasPath('_meta', $json);
        Assert::assertJsonHasPath('products', $json);
        Assert::assertJsonPathCount(2, 'products.*.id', $json);
        Assert::assertJsonPathEquals($invoice->products[0]->id, 'products.0.id', $json, 1);
        Assert::assertJsonPathEquals($invoice->products[1]->id, 'products.1.id', $json, 1);
    }

    public function testWriteOffProductsReferenceManyIsSerialized()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $store = $this->factory()->store()->getStore();

        $writeOff = $this->factory()
            ->writeOff()
                ->createWriteOff($store)
                ->createWriteOffProduct($productId1, 10, 12.8, '*')
                ->createWriteOffProduct($productId2, 5, 18.7, '**')
            ->flush();

        $metaDocument = new MetaDocument($writeOff);
        $serializer = $this->getSerializer();
        $jsonData = $serializer->serialize($metaDocument, 'json');
        $json = json_decode($jsonData, true);
        Assert::assertJsonHasPath('_meta', $json);
        Assert::assertJsonHasPath('products', $json);
        Assert::assertJsonPathCount(2, 'products.*.id', $json);
        Assert::assertJsonPathEquals($writeOff->products[0]->id, 'products.0.id', $json, 1);
        Assert::assertJsonPathEquals($writeOff->products[1]->id, 'products.1.id', $json, 1);
    }
}
