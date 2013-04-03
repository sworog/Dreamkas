<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
 */
class InvoiceProductListener
{
    public function postPersist(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $diff = $this->getPropertyDiff($event, 'quantity');
            $this->updateProductAmount(
                $event->getDocumentManager(),
                $event->getDocument()->product,
                $diff
            );
        }
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $diff = $this->getPropertyDiff($event, 'quantity');
            $this->updateProductAmount(
                $event->getDocumentManager(),
                $event->getDocument()->product,
                $diff
            );
        }
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $invoiceProduct = $event->getDocument();
            $this->updateProductAmount(
                $event->getDocumentManager(),
                $event->getDocument()->product,
                $invoiceProduct->quantity * -1
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
     * @param int $quantityDiff
     */
    protected function updateProductAmount(DocumentManager $manager, Product $product, $quantityDiff)
    {
        if ($quantityDiff <> 0) {
            /* @var ShopProduct $shopProduct */
            $query = $manager
                ->createQueryBuilder('LighthouseCoreBundle:ShopProduct')
                ->findAndUpdate()
                ->field('product')->equals($product->id)
                ->field('amount')->inc($quantityDiff)
                ->returnNew() // is needed for ShopProduct to be updated in IdentityMap
                ->upsert()
                ->getQuery();
            $query->execute();
        }
    }
}