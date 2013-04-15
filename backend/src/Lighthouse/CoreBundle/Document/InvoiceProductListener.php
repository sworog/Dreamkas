<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
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

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $quantityDiff = $this->getPropertyDiff($eventArgs, 'quantity');

            $this->productRepository->updateAmountAndLastPurchasePrice(
                $document->product,
                $quantityDiff,
                $document->price
            );

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

            $quantityDiff = $this->getPropertyDiff($eventArgs, 'quantity');

            $this->productRepository->updateAmountAndLastPurchasePrice(
                $document->product,
                $quantityDiff,
                $document->price
            );
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        if ($eventArgs->getDocument() instanceof InvoiceProduct) {
            $invoiceProduct = $eventArgs->getDocument();

            $quantityDiff = $this->getPropertyDiff($eventArgs, 'quantity');

            $this->productRepository->updateAmountAndLastPurchasePrice(
                $invoiceProduct->product,
                $quantityDiff - $invoiceProduct->quantity
            );
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
        $changeSet = $uow->getDocumentChangeSet($document);
        if (isset($changeSet[$propertyName])) {
            return $this->propertyToInt($changeSet[$propertyName][1]) - $this->propertyToInt($changeSet[$propertyName][0]);
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
