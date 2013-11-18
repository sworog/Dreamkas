<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Quantity;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preRemove", "onFlush"})
 */
class AmountListener extends AbstractMongoDBListener
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(StoreProductRepository $storeProductRepository)
    {
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof Reasonable) {
            $storeProduct = $this->getStoreProduct($document);
            $sign = ($document->increaseAmount()) ? 1 : -1;
            $storeProduct->inventory = $storeProduct->inventory + ($document->getProductQuantity()->toNumber() * $sign);
            $eventArgs->getDocumentManager()->persist($storeProduct);
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
            $storeProduct->inventory = $storeProduct->inventory - ($document->getProductQuantity()->toNumber() * $sign);
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

            $oldProduct->inventory = $oldProduct->inventory + $oldQuantity * $sign;
            $this->computeChangeSet($dm, $oldProduct);

            $newProduct->inventory = $newProduct->inventory - $newQuantity * $sign;
            $dm->persist($newProduct);
            $this->computeChangeSet($dm, $newProduct);
        } else {
            if (isset($changeSet['quantity'])) {
                $quantityDiff = ($changeSet['quantity'][0]->toNumber() - $changeSet['quantity'][1]->toNumber()) * $sign;
            } else {
                $quantityDiff = 0;
            }

            $storeProduct = $this->getStoreProduct($document);
            $storeProduct->inventory = $storeProduct->inventory + $quantityDiff;
            $this->computeChangeSet($dm, $storeProduct);
        }
    }

    /**
     * @param Reasonable $reason
     * @param Product $product
     * @return StoreProduct
     */
    protected function getStoreProduct(Reasonable $reason, Product $product = null)
    {
        if ($product instanceof ProductVersion) {
            $product = $product->getObject();
        } elseif (null === $product) {
            $product = $reason->getReasonProduct();
        }
        $store = $reason->getReasonParent()->getStore();
        return $this->storeProductRepository->findOrCreateByStoreProduct($store, $product);
    }

    /**
     * @param Product|ProductVersion $product
     * @return Product
     */
    protected function normalizeProduct(Product $product)
    {
        if ($product instanceof ProductVersion) {
            $product = $product->getObject();
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
            $oldProduct = $this->normalizeProduct($changeSet['product'][0]);
            $newProduct = $this->normalizeProduct($changeSet['product'][1]);
            return $oldProduct->id != $newProduct->id;
        } else {
            return false;
        }
    }
}
