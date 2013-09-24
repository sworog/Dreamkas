<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;

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

        if ($document instanceof Reasonable) {
            $storeProduct = $this->getStoreProduct($document);
            $sign = ($document->increaseAmount()) ? 1 : -1;
            $storeProduct->amount = $storeProduct->amount + ($document->getProductQuantity() * $sign);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof Reasonable) {
            $storeProduct = $this->getStoreProduct($document);
            $sign = ($document->increaseAmount()) ? 1 : -1;
            $storeProduct->amount = $storeProduct->amount - ($document->getProductQuantity() * $sign);
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
            if ($document instanceof Reasonable) {
                $this->updateProductOnFlush($dm, $document);
            }
        }
    }

    /**
     * @param DocumentManager $dm
     * @param Reasonable $document
     */
    public function updateProductOnFlush(DocumentManager $dm, Reasonable $document)
    {
        $sign = ($document->increaseAmount()) ? -1 : 1;

        $uow = $dm->getUnitOfWork();

        $changeSet = $uow->getDocumentChangeSet($document);

        if ($this->isProductChanged($changeSet)) {
            $oldProduct = $this->getStoreProduct($document, $changeSet['product'][0]);
            $newProduct = $this->getStoreProduct($document, $changeSet['product'][1]);

            $oldQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][0] : $document->getProductQuantity();
            $newQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][1] : $document->getProductQuantity();

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

            $storeProduct = $this->getStoreProduct($document);
            $storeProduct->amount = $storeProduct->amount + $quantityDiff;
            $this->computeChangeSet($dm, $storeProduct);
        }
    }

    /**
     * @param Reasonable $reason
     * @param Product $product
     * @return Product
     */
    protected function getStoreProduct(Reasonable $reason, Product $product = null)
    {
        if ($product instanceof ProductVersion) {
            $product = $product->getObject();
        } elseif (null === $product) {
            $product = $reason->getReasonProduct();
        }
        return $product;
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
