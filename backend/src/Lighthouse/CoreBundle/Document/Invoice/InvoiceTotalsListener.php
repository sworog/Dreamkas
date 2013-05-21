<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\InvoiceProduct\InvoiceProduct;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove"})
 */
class InvoiceTotalsListener extends AbstractMongoDBListener
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
            $totalPriceDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPrice');
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
            $totalPriceDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPrice');
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
}
