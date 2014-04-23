<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Symfony\Component\Validator\ObjectInitializerInterface;

/**
 * @DI\Service("lighthouse.core.document.invoice.totals_listener")
 * @DI\Tag("validator.initializer")
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postUpdate", "postRemove", "postFlush"})
 */
class InvoiceTotalsListener extends AbstractMongoDBListener implements ObjectInitializerInterface
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
     * @var \SplQueue|Invoice[]
     */
    protected $invoicesQueue;

    /**
     * @var int
     */
    protected $postFlushCount = 0;

    /**
     * @DI\InjectParams({
     *     "invoiceRepository" = @DI\Inject("lighthouse.core.document.repository.invoice"),
     *     "invoiceProductRepository" = @DI\Inject("lighthouse.core.document.repository.invoice_product")
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

        $this->invoicesQueue = new \SplQueue();
        $this->invoicesQueue->setIteratorMode(\SplQueue::IT_MODE_DELETE);
    }

    /**
     * @param object $object
     */
    public function initialize($object)
    {
        if ($object instanceof Invoice) {
            $object->calculateTotals();
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $this->invoicesQueue->enqueue($document->invoice);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof InvoiceProduct) {
            $this->invoicesQueue->enqueue($document->invoice);
        }

        if ($document instanceof Invoice && false) {
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
            $this->invoicesQueue->enqueue($document->invoice);
        }
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     * @throws \InvalidArgumentException
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (false && 0 == $this->postFlushCount++ && !$this->invoicesQueue->isEmpty()) {
            $dm = $eventArgs->getDocumentManager();

            foreach ($this->invoicesQueue as $invoice) {
                //$dm->refresh($invoice);
                $invoice->calculateTotals();
                $dm->persist($invoice);
            }

            $dm->flush();
        }
        $this->postFlushCount--;
    }
}
