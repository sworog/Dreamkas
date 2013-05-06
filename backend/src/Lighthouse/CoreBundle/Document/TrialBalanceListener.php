<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\InvoiceProduct;

/**
 * Class TrialBalanceListener
 *
 * @DI\DoctrineMongoDBListener(events={"onFlush"})
 */
class TrialBalanceListener
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.trial_balance")
     * @var TrialBalanceRepository
     */
    public $trialBalanceRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice_product")
     * @var InvoiceProductRepository
     */
    public $invoiceProductRepository;

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        /* @var DocumentManager $dm */
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentInsertions() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = new TrialBalance();

                $trialBalance->price = $document->price;
                $trialBalance->quantity = $document->quantity;
                $trialBalance->product = $document->product;
                $trialBalance->reason = $document;
                $trialBalance->createdDate = $document->invoice->acceptanceDate;

                $dm->persist($trialBalance);

                $this->computeChangeSet($dm, $uow, $trialBalance);
            }
        }

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = $this->trialBalanceRepository->findOneByReason($document);

                $trialBalance->price = $document->price;
                $trialBalance->quantity = $document->quantity;
                $trialBalance->product = $document->product;

                $dm->persist($trialBalance);

                $this->computeChangeSet($dm, $uow, $trialBalance);
            }

            if ($document instanceof Invoice) {
                $this->processInvoiceOnUpdate($document, $dm, $uow);
            }
        }

        foreach ($uow->getScheduledDocumentDeletions() as $document) {
            if ($document instanceof InvoiceProduct) {
                $trialBalance = $this->trialBalanceRepository->findOneByReason($document);
                $dm->remove($trialBalance);

                $this->computeChangeSet($dm, $uow, $trialBalance);
            }
        }
    }

    /**
     * @param Invoice $invoice
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     */
    protected function processInvoiceOnUpdate(Invoice $invoice, DocumentManager $dm, UnitOfWork $uow)
    {
        $changeSet = $uow->getDocumentChangeSet($invoice);
        if (!isset($changeSet['acceptanceDate'])) {
            return;
        }
        $newAcceptanceDate = $changeSet['acceptanceDate'][1];

        /* @var Cursor $invoiceProducts */
        $invoiceProducts = $this->invoiceProductRepository->findByInvoice($invoice->id);
        $trailBalances = $this->trialBalanceRepository->findByReasons($invoiceProducts);

        foreach ($trailBalances as $trailBalance) {
            $trailBalance->createdDate = $newAcceptanceDate;
            $this->computeChangeSet($dm, $uow, $trailBalance);
        }
    }

    /**
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param AbstractDocument $document
     */
    protected function computeChangeSet(DocumentManager $dm, UnitOfWork $uow, AbstractDocument $document)
    {
        $metadata = $dm->getClassMetadata(get_class($document));
        $uow->computeChangeSet($metadata, $document);
    }
}