<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Purchase\Product\PurchaseProduct;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preRemove", "onFlush"})
 */
class AmountListener extends AbstractMongoDBListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof Productable) {
            $product = $document->getReasonProduct();
            $sign = ($document->increaseAmount()) ? 1 : -1;
            $product->amount = $product->amount + ($document->getProductQuantity() * $sign);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof Productable) {
            $product = $document->getReasonProduct();
            $sign = ($document->increaseAmount()) ? 1 : -1;
            $product->amount = $product->amount - ($document->getProductQuantity() * $sign);
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
            if ($document instanceof Productable) {
                $this->updateProductOnFlush($dm, $document);
            }
        }
    }

    /**
     * @param DocumentManager $dm
     * @param Productable $productDocument
     */
    public function updateProductOnFlush(DocumentManager $dm, Productable $productDocument)
    {
        $sign = ($productDocument->increaseAmount()) ? -1 : 1;

        $uow = $dm->getUnitOfWork();

        $changeSet = $uow->getDocumentChangeSet($productDocument);

        if ($this->isProductChanged($changeSet)) {
            $oldProduct = $changeSet['product'][0];
            $newProduct = $changeSet['product'][1];

            if ($oldProduct instanceof ProductVersion) {
                $oldProduct = $oldProduct->getObject();
            }
            if ($newProduct instanceof ProductVersion) {
                $newProduct = $newProduct->getObject();
            }

            $oldQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][0] : $productDocument->getProductQuantity();
            $newQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][1] : $productDocument->getProductQuantity();

            $oldProduct->amount = $oldProduct->amount + $oldQuantity * $sign;
            $this->computeChangeSet($dm, $oldProduct);

            $newProduct->amount = $newProduct->amount - $newQuantity * $sign;
            $this->computeChangeSet($dm, $newProduct);
        } else {
            if (isset($changeSet['quantity'])) {
                $quantityDiff = ($changeSet['quantity'][0] - $changeSet['quantity'][1]) * $sign;
            } else {
                $quantityDiff = 0;
            }

            $product = $productDocument->getReasonProduct();
            $product->amount = $product->amount + $quantityDiff;
            $this->computeChangeSet($dm, $product);
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
}
