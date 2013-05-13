<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\InvoiceProduct\InvoiceProduct;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
 */
class TotalsListener
{
    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @DI\InjectParams({
     *     "invoiceRepository"=@DI\Inject("lighthouse.core.document.repository.invoice")
     * })
     *
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
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
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $this->invoiceRepository->updateTotals($document->invoice, -1, $document->totalPrice->getCount() * -1);
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
