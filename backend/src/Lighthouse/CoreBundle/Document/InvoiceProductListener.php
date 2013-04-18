<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "postPersist", "postUpdate", "preRemove", "onFlush"})
 */
class InvoiceProductListener
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @DI\InjectParams({
     *     "productRepository"=@DI\Inject("lighthouse.core.document.repository.product"),
     *     "invoiceRepository"=@DI\Inject("lighthouse.core.document.repository.invoice")
     * })
     *
     * @param ProductRepository $productRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(ProductRepository $productRepository, InvoiceRepository $invoiceRepository)
    {
        $this->productRepository = $productRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $document->product->amount = $document->product->amount + $document->quantity;
            $document->product->lastPurchasePrice = new Money($document->price);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $totalPriceDiff = $this->getPropertyDiff($eventArgs, 'totalPrice');
            $this->invoiceRepository->updateTotals($document->invoice, 1, $totalPriceDiff);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {

            $totalPriceDiff = $this->getPropertyDiff($eventArgs, 'totalPrice');
            $this->invoiceRepository->updateTotals($document->invoice, 0, $totalPriceDiff);
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
            $oldProduct->lastPurchasePrice = null;
            $this->computeChangeSet($dm, $oldProduct);

            $newProduct->amount = $newProduct->amount + $newQuantity;
            $newProduct->lastPurchasePrice = new Money($invoiceProduct->price);
            $this->computeChangeSet($dm, $newProduct);
        } else {

            $quantityDiff = $this->computeDiff($changeSet, 'quantity');

            $invoiceProduct->product->amount = $invoiceProduct->product->amount + $quantityDiff;
            $invoiceProduct->product->lastPurchasePrice = new Money($invoiceProduct->price);
            $this->computeChangeSet($dm, $invoiceProduct->product);
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
     * @param LifecycleEventArgs $eventArgs
     * @param string $propertyName
     * @return int
     */
    protected function getPropertyDiff(LifecycleEventArgs $eventArgs, $propertyName)
    {
        $document = $eventArgs->getDocument();
        $uow = $eventArgs->getDocumentManager()->getUnitOfWork();
        $change = $uow->getDocumentChangeSet($document);
        return $this->computeDiff($change, $propertyName);
    }

    /**
     * @param array $change
     * @param string $propertyName
     * @return int
     */
    protected function computeDiff(array $change, $propertyName)
    {
        if (isset($change[$propertyName])) {
            return $this->propertyToInt($change[$propertyName][1]) - $this->propertyToInt($change[$propertyName][0]);
        } else {
            return 0;
        }
    }

    /**
     * @param Money|integer $value
     * @return int
     */
    protected function propertyToInt($value)
    {
        if ($value instanceof Money) {
            return (int) $value->getCount();
        } else {
            return (int) $value;
        }
    }
}
