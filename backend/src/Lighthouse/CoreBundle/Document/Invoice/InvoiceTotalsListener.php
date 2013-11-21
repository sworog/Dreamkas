<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;

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
     * @var InvoiceProductRepository
     */
    protected $invoiceProductRepository;

    /**
     * @DI\InjectParams({
     *     "invoiceRepository"=@DI\Inject("lighthouse.core.document.repository.invoice"),
     *     "invoiceProductRepository"=@DI\Inject("lighthouse.core.document.repository.invoice_product")
     * })
     *
     * @param InvoiceRepository $invoiceRepository
     * @param InvoiceProductRepository $invoiceProductRepository
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
        InvoiceProductRepository $invoiceProductRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceProductRepository = $invoiceProductRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $totalPriceDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPrice');
            $totalPriceWithoutVATDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPriceWithoutVAT');
            $totalAmountVATDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalAmountVAT');
            $this->invoiceRepository->updateTotals(
                $document->invoice,
                1,
                $totalPriceDiff,
                $totalPriceWithoutVATDiff,
                $totalAmountVATDiff
            );
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
            $totalPriceWithoutVATDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalPriceWithoutVAT');
            $totalAmountVATDiff = $this->getChangeSetIntPropertyDiff($eventArgs, 'totalAmountVAT');
            $this->invoiceRepository->updateTotals(
                $document->invoice,
                0,
                $totalPriceDiff,
                $totalPriceWithoutVATDiff,
                $totalAmountVATDiff
            );
        }

        if ($document instanceof Invoice) {
            $changeSet = $this->getDocumentChangesSet($eventArgs);
            if (array_key_exists('includesVAT', $changeSet)) {
                $this->invoiceProductRepository->recalcVATByInvoice($document);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $this->invoiceRepository->updateTotals(
                $document->invoice,
                -1,
                $document->totalPrice->getCount() * -1,
                $document->totalPriceWithoutVAT->getCount() * -1,
                $document->totalAmountVAT->getCount() * -1
            );
        }
    }
}
