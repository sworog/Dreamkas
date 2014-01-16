<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preRemove", "onFlush"})
 */
class InventoryListener extends AbstractMongoDBListener
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
            $inventoryDiff = $document->getProductQuantity()->sign($document->increaseAmount());
            $storeProduct->inventory = $storeProduct->inventory->add($inventoryDiff);
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
            $inventoryDiff = $document->getProductQuantity()->sign($document->increaseAmount());
            $storeProduct->inventory = $storeProduct->inventory->sub($inventoryDiff);
            $eventArgs->getDocumentManager()->persist($storeProduct);
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
        $uow = $dm->getUnitOfWork();

        $changeSet = $uow->getDocumentChangeSet($document);

        if ($this->isProductChanged($changeSet)) {
            $oldStoreProduct = $this->getStoreProduct($document, $changeSet['product'][0]);
            $newStoreProduct = $this->getStoreProduct($document, $changeSet['product'][1]);

            $oldQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][0] : $document->getProductQuantity();
            $newQuantity = isset($changeSet['quantity']) ? $changeSet['quantity'][1] : $document->getProductQuantity();

            $oldInventoryDiff = $oldQuantity->sign(!$document->increaseAmount());
            $oldStoreProduct->inventory = $oldStoreProduct->inventory->add($oldInventoryDiff);
            $this->computeChangeSet($dm, $oldStoreProduct);

            $newInventoryDiff = $newQuantity->sign(!$document->increaseAmount());
            $newStoreProduct->inventory = $newStoreProduct->inventory->sub($newInventoryDiff);
            $dm->persist($newStoreProduct);
            $this->computeChangeSet($dm, $newStoreProduct);
        } else {
            if (isset($changeSet['quantity'])) {
                /* @var Quantity $quantity0 */
                $quantity0 = $changeSet['quantity'][0];
                /* @var Quantity $quantity1 */
                $quantity1 = $changeSet['quantity'][1];
                $quantityDiff = $quantity1->sub($quantity0)->sign($document->increaseAmount());
            } else {
                $quantityDiff = 0;
            }

            $storeProduct = $this->getStoreProduct($document);
            $storeProduct->inventory = $storeProduct->inventory->add($quantityDiff);
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
