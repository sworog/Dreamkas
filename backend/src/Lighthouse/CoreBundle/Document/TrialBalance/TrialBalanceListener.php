<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use SplQueue;

/**
 * Class TrialBalanceListener
 *
 * @DI\DoctrineMongoDBListener(events={"onFlush", "postFlush"})
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
     * @var CostOfGoodsCalculator
     */
    protected $costOfGoodsCalculator;

    /**
     * @var SplQueue|TrialBalance[]
     */
    protected $trialBalanceQueue;

    /**
     * @var int
     */
    protected $postFlushCounter = 0;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "invoiceProductRepository" = @DI\Inject("lighthouse.core.document.repository.invoice_product"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "costOfGoodsCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator"),
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param InvoiceProductRepository $invoiceProductRepository
     * @param StoreProductRepository $storeProductRepository
     * @param CostOfGoodsCalculator $costOfGoodsCalculator
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        InvoiceProductRepository $invoiceProductRepository,
        StoreProductRepository $storeProductRepository,
        CostOfGoodsCalculator $costOfGoodsCalculator
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->invoiceProductRepository = $invoiceProductRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->costOfGoodsCalculator = $costOfGoodsCalculator;

        $this->trialBalanceQueue = new SplQueue();
        $this->trialBalanceQueue->setIteratorMode(SplQueue::IT_MODE_DELETE);
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

    protected function processSupportsRangeIndexRemove(
        Reasonable $document,
        TrialBalance $trialBalance,
        DocumentManager $dm
    ) {
        $nextProcessedTrialBalance = $this->trialBalanceRepository->findOneNext($trialBalance);

        if (null != $nextProcessedTrialBalance) {
            $nextProcessedTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
            $dm->persist($nextProcessedTrialBalance);
            $this->computeChangeSet($dm, $nextProcessedTrialBalance);
        }

        if ($document->increaseAmount()) {
            $this->processIncreaseSupportsRangeIndexReferenceSetNeedProcessing($trialBalance, $dm);
        }
    }

    protected function processIncreaseSupportsRangeIndexReferenceSetNeedProcessing(
        TrialBalance $trialBalance,
        DocumentManager $dm
    ) {
        if (null != $trialBalance->startIndex && null != $trialBalance->endIndex) {
            $referenceTrialBalance = $this
                ->trialBalanceRepository
                ->findOneByIndexRange(
                    $this->costOfGoodsCalculator->getSupportCostOfGoods(),
                    $trialBalance->storeProduct->id,
                    $trialBalance->startIndex,
                    $trialBalance->endIndex
                );
            if (null != $referenceTrialBalance) {
                $referenceTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                $dm->persist($referenceTrialBalance);
                $this->computeChangeSet($dm, $referenceTrialBalance);
            }
        }
    }

    /**
     * @param Reasonable $document
     * @param $trialBalance
     * @param $storeProduct
     * @param DocumentManager $dm
     */
    protected function processSupportsRangeIndexUpdate(
        Reasonable $document,
        $trialBalance,
        $storeProduct,
        DocumentManager $dm
    ) {
        if ($storeProduct != $trialBalance->storeProduct) {
            $needProcessedTrialProduct = $this->trialBalanceRepository->findOnePrevious($trialBalance);
            if (null == $needProcessedTrialProduct) {
                $needProcessedTrialProduct = $this->trialBalanceRepository->findOneNext($trialBalance);
            }
            if (null != $needProcessedTrialProduct) {
                $needProcessedTrialProduct->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                $dm->persist($needProcessedTrialProduct);
                $this->computeChangeSet($dm, $needProcessedTrialProduct);

                if ($document->increaseAmount()) {
                    $this->processIncreaseSupportsRangeIndexReferenceSetNeedProcessing($needProcessedTrialProduct, $dm);
                }
            }
        }

        $trialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;

        if ($document->increaseAmount()) {
            $this->processIncreaseSupportsRangeIndexReferenceSetNeedProcessing($trialBalance, $dm);
        }
    }

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonablePersist(Reasonable $document, DocumentManager $dm)
    {
        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);
        $dm->persist($storeProduct);
        $this->computeChangeSet($dm, $storeProduct);

        $trialBalance = new TrialBalance();
        $trialBalance->price = $document->getProductPrice();
        $trialBalance->quantity = $document->getProductQuantity();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->reason = $document;
        $trialBalance->createdDate = $document->getReasonDate();

        $this->trialBalanceQueue[] = $trialBalance;
    }
    
    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonableUpdate(Reasonable $document, DocumentManager $dm)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByReason($document);

        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);

        if ($this->costOfGoodsCalculator->supportsRangeIndex($document)) {
            $this->processSupportsRangeIndexUpdate($document, $trialBalance, $storeProduct, $dm);
        }

        $trialBalance->price = $document->getProductPrice();
        $trialBalance->quantity = $document->getProductQuantity();
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

        if ($this->costOfGoodsCalculator->supportsRangeIndex($document)) {
            $this->processSupportsRangeIndexRemove($document, $trialBalance, $dm);
        }

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

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (!$this->trialBalanceQueue->isEmpty() && 0 == $this->postFlushCounter) {
            $this->postFlushCounter++;
            $dm = $eventArgs->getDocumentManager();
            foreach ($this->trialBalanceQueue as $trialBalance) {
                $dm->persist($trialBalance);
            }
            $dm->flush();
            $this->postFlushCounter--;
        }
    }
}
