<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;

/**
 * Class TrialBalanceListener
 *
 * @DI\DoctrineMongoDBListener(events={"onFlush"})
 */
class TrialBalanceListener extends AbstractMongoDBListener
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var InvoiceProductRepository
     */
    protected $invoiceProductRepository;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "invoiceProductRepository" = @DI\Inject("lighthouse.core.document.repository.invoice_product"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param InvoiceProductRepository $invoiceProductRepository
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        InvoiceProductRepository $invoiceProductRepository,
        StoreProductRepository $storeProductRepository
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->invoiceProductRepository = $invoiceProductRepository;
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        /* @var DocumentManager $dm */
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentInsertions() as $document) {
            if ($document instanceof Reasonable) {
                $this->onReasonablePersist($document, $dm);
            }
        }

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof Reasonable) {
                $this->onReasonableUpdate($document, $dm);
            }

            if ($document instanceof Invoice) {
                $this->processInvoiceOnAcceptanceDateUpdate($document, $dm, $uow);
            }
        }

        foreach ($uow->getScheduledDocumentDeletions() as $document) {
            if ($document instanceof Reasonable) {
                $this->onReasonableRemove($document, $dm);
            }
        }
    }

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonablePersist(Reasonable $document, DocumentManager $dm)
    {
        $trialBalance = new TrialBalance();

        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);

        $trialBalance->price = $document->getProductPrice();
        $trialBalance->quantity = $document->getProductQuantity()->toNumber();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->reason = $document;
        $trialBalance->createdDate = $document->getReasonDate();

        $dm->persist($storeProduct);
        $dm->persist($trialBalance);

        $this->computeChangeSet($dm, $trialBalance);
    }
    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonableUpdate(Reasonable $document, DocumentManager $dm)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByReason($document);

        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);

        $trialBalance->price = $document->getProductPrice();
        $trialBalance->quantity = $document->getProductQuantity()->toNumber();
        $trialBalance->storeProduct = $storeProduct;

        $dm->persist($storeProduct);
        $dm->persist($trialBalance);

        $this->computeChangeSet($dm, $trialBalance);
    }

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonableRemove(Reasonable $document, DocumentManager $dm)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByReason($document);
        $dm->remove($trialBalance);

        $this->computeChangeSet($dm, $trialBalance);
    }

    /**
     * @param Invoice $invoice
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     */
    protected function processInvoiceOnAcceptanceDateUpdate(Invoice $invoice, DocumentManager $dm, UnitOfWork $uow)
    {
        $changeSet = $uow->getDocumentChangeSet($invoice);
        if (!isset($changeSet['acceptanceDate'])) {
            return;
        }
        $newAcceptanceDate = $changeSet['acceptanceDate'][1];

        /* @var Reasonable[] $invoiceProducts */
        $invoiceProducts = $this->invoiceProductRepository->findByInvoice($invoice->id);
        $trailBalances = $this->trialBalanceRepository->findByReasons($invoiceProducts);

        foreach ($trailBalances as $trailBalance) {
            $trailBalance->createdDate = $newAcceptanceDate;
            $this->computeChangeSet($dm, $trailBalance);
        }
    }
}
