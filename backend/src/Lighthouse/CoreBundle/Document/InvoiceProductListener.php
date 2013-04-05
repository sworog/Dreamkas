<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
 */
class InvoiceProductListener
{
    /**
     * @var ShopProductRepository
     */
    protected $shopProductRepository;

    /**
     * @DI\InjectParams({
     *     "shopProductRepository"=@DI\Inject("lighthouse.core.document.repository.shop_product")
     * })
     *
     * @param ShopProductRepository $shopProductRepository
     */
    public function __construct(ShopProductRepository $shopProductRepository)
    {
        $this->shopProductRepository = $shopProductRepository;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $diff = $this->getPropertyDiff($event, 'quantity');
            $this->updateProductAmount(
                $event->getDocument()->product,
                $diff
            );
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $diff = $this->getPropertyDiff($event, 'quantity');
            $this->updateProductAmount(
                $event->getDocument()->product,
                $diff
            );
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postRemove(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $invoiceProduct = $event->getDocument();
            $diff = $this->getPropertyDiff($event, 'quantity');
            $this->updateProductAmount(
                $invoiceProduct->product,
                $diff - $invoiceProduct->quantity
            );
        }
    }

    /**
     * @param LifecycleEventArgs $event
     * @param string $propertyName
     * @return int
     */
    protected function getPropertyDiff(LifecycleEventArgs $event, $propertyName)
    {
        $document = $event->getDocument();
        $uow = $event->getDocumentManager()->getUnitOfWork();
        $changeSet = $uow->getDocumentChangeSet($document);
        if (isset($changeSet[$propertyName])) {
            return $changeSet[$propertyName][1] - $changeSet[$propertyName][0];
        } else {
            return 0;
        }
    }

    /**
     * @param DocumentManager $manager
     * @param Product $product
     * @param int $amountDiff
     */
    protected function updateProductAmount(Product $product, $amountDiff)
    {
        if ($amountDiff <> 0) {
            $this->shopProductRepository->updateAmount($product, $amountDiff);
        }
    }
}