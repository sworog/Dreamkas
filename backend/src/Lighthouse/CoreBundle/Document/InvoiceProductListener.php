<?php

namespace Lighthouse\CoreBundle\Document;

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
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {
            $quantityDiff = $this->getPropertyDiff($event, 'quantity');

            $this->updateProductAmount(
                $event->getDocument()->product,
                $quantityDiff
            );

            $totalPriceDiff = $this->getPropertyDiff($event, 'totalPrice');

            $this->updateInvoiceTotals(
                $event->getDocument()->invoice,
                1,
                $totalPriceDiff
            );
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        if ($event->getDocument() instanceof InvoiceProduct) {

            $quantityDiff = $this->getPropertyDiff($event, 'quantity');

            $this->updateProductAmount(
                $event->getDocument()->product,
                $quantityDiff
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

            $quantityDiff = $this->getPropertyDiff($event, 'quantity');

            $this->updateProductAmount(
                $invoiceProduct->product,
                $quantityDiff - $invoiceProduct->quantity
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

    /**
     * @param Product $product
     * @param int $amountDiff
     */
    protected function updateProductAmount(Product $product, $amountDiff)
    {
        if ($amountDiff <> 0) {
            $this->productRepository->updateAmount($product, $amountDiff);
        }
    }

    /**
     * @param Invoice $invoice
     * @param int $itemsCountDiff
     * @param int $totalSumDiff
     */
    protected function updateInvoiceTotals(Invoice $invoice, $itemsCountDiff, $totalSumDiff)
    {
        $this->invoiceRepository->updateTotals($invoice, $itemsCountDiff, $totalSumDiff);
    }
}
