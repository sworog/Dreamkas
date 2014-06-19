<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Job\JobManager;

/**
 * @DI\DoctrineMongoDBListener(events={"onFlush"}, priority=128)
 */
class TrialBalanceListener extends AbstractMongoDBListener
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var CostOfGoodsCalculator
     */
    protected $costOfGoodsCalculator;

    /**
     * @var JobManager
     */
    protected $jobManager;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "costOfGoodsCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator"),
     *      "jobManager" = @DI\Inject("lighthouse.core.job.manager")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param StoreProductRepository $storeProductRepository
     * @param CostOfGoodsCalculator $costOfGoodsCalculator
     * @param JobManager $jobManager
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        StoreProductRepository $storeProductRepository,
        CostOfGoodsCalculator $costOfGoodsCalculator,
        JobManager $jobManager
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->costOfGoodsCalculator = $costOfGoodsCalculator;
        $this->jobManager = $jobManager;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
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
        $trialBalanceProcessJob = new TrialBalanceProcessJob();
        $trialBalanceProcessJob->setReason($document);
        $trialBalanceProcessJob->processType = TrialBalanceProcessJob::PROCESS_TYPE_CREATE;

        $this->jobManager->addJob($trialBalanceProcessJob);
    }
    
    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonableUpdate(Reasonable $document, DocumentManager $dm)
    {
        $trialBalanceProcessJob = new TrialBalanceProcessJob();
        $trialBalanceProcessJob->setReason($document);
        $trialBalanceProcessJob->processType = TrialBalanceProcessJob::PROCESS_TYPE_UPDATE;

        $this->jobManager->addJob($trialBalanceProcessJob);
    }

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonableRemove(Reasonable $document, DocumentManager $dm)
    {
        $trialBalanceProcessJob = new TrialBalanceProcessJob();
        $trialBalanceProcessJob->setReason($document);
        $trialBalanceProcessJob->processType = TrialBalanceProcessJob::PROCESS_TYPE_REMOVE;

        $this->jobManager->addJob($trialBalanceProcessJob);
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
        $oldAcceptanceDate = $changeSet['acceptanceDate'][0];
        $newAcceptanceDate = $changeSet['acceptanceDate'][1];

        /* @var InvoiceProduct[] $invoiceProducts */
        $invoiceProducts = $invoice->products;
        $trialBalances = $this->trialBalanceRepository->findByReasons($invoiceProducts);

        foreach ($invoiceProducts as $invoiceProduct) {
            $invoiceProduct->beforeSave();
            $this->computeChangeSet($dm, $invoiceProduct);
        }

        foreach ($trialBalances as $trialBalance) {
            if ($newAcceptanceDate > $oldAcceptanceDate) {
                $nextTrialBalance = $this->trialBalanceRepository->findOneNext($trialBalance);
                if (null != $nextTrialBalance) {
                    $nextTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                    $this->computeChangeSet($dm, $nextTrialBalance);
                }
            }

            $trialBalance->createdDate = clone $newAcceptanceDate;
            $trialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
            $this->computeChangeSet($dm, $trialBalance);
        }
    }
}
