<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\InvoiceProduct\InvoiceProduct;
use Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preRemove", "onFlush"})
 */
class AmountListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $document->product->amount = $document->product->amount + $document->quantity;
        }

        if ($document instanceof PurchaseProduct) {
            $document->product->amount = $document->product->amount - $document->quantity;
        }

        if ($document instanceof WriteOffProduct) {
            $document->product->amount = $document->product->amount - $document->quantity;
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $document->product->amount = $document->product->amount - $document->quantity;
        }
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        /* @var DocumentManager $dm */
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof InvoiceProduct) {
                $this->updateProductOnFlush($dm, $document);
            }
        }
    }

    /**
     * @param DocumentManager $dm
     * @param InvoiceProduct $invoiceProduct
     */
    public function updateProductOnFlush(DocumentManager $dm, InvoiceProduct $invoiceProduct)
    {
        $uow = $dm->getUnitOfWork();

        $changeSet = $uow->getDocumentChangeSet($invoiceProduct);

        if ($this->isProductChanged($changeSet)) {
            $oldProduct = $changeSet['product'][0];
            $newProduct = $changeSet['product'][1];

            $oldQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][0] : $invoiceProduct->quantity;
            $newQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][1] : $invoiceProduct->quantity;

            $oldProduct->amount = $oldProduct->amount - $oldQuantity;
            $this->computeChangeSet($dm, $oldProduct);

            $newProduct->amount = $newProduct->amount + $newQuantity;
            $this->computeChangeSet($dm, $newProduct);
        } else {
            if (isset($changeSet['quantity'])) {
                $quantityDiff = $changeSet['quantity'][1] - $changeSet['quantity'][0];
            } else {
                $quantityDiff = 0;
            }

            $invoiceProduct->product->amount = $invoiceProduct->product->amount + $quantityDiff;
            $this->computeChangeSet($dm, $invoiceProduct->product);
        }
    }

    /**
     * Check if product reference was changed
     *
     * @param array $changeSet
     * @return bool
     */
    protected function isProductChanged(array $changeSet)
    {
        if (isset($changeSet['product'])) {
            return $changeSet['product'][1]->id != $changeSet['product'][0]->id;
        } else {
            return false;
        }
    }

    /**
     * Helper method for computing changes set
     *
     * @param DocumentManager $dm
     * @param $document
     */
    protected function computeChangeSet(DocumentManager $dm, $document)
    {
        $uow = $dm->getUnitOfWork();
        $class = $dm->getClassMetadata(get_class($document));
        $uow->computeChangeSet($class, $document);
    }
}
