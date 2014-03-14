<?php

namespace Lighthouse\CoreBundle\Document\Order;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Order\Product\OrderProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preUpdate"})
 */
class OrderListener extends AbstractMongoDBListener
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *     "storeProductRepository"=@DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     *
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

        if ($document instanceof OrderProduct) {
            $document->storeProduct = $this
                ->storeProductRepository
                ->findOrCreateByStoreProduct($document->order->store, $document->product);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof OrderProduct) {
            $document->storeProduct = $this
                ->storeProductRepository
                ->getReference(
                    $this
                        ->storeProductRepository
                        ->getIdByStoreAndProduct($document->order->store, $document->product)
                );
        }
    }
}
