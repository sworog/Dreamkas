<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use SplQueue;

/**
 * Class TrialBalanceListener
 *
 * @DI\DoctrineMongoDBListener(events={"onFlush", "preFlush", "postFlush"}, priority=128)
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
     *      "costOfGoodsCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator")
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
     * @param PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof Invoice) {
                $this->processInvoiceOnAcceptanceDateUpdate($document, $dm, $uow);
            }
        }
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
     * @param TrialBalance $trialBalance
     * @param DocumentManager $dm
     */
    protected function processSupportsRangeIndexRemove(TrialBalance $trialBalance, DocumentManager $dm)
    {
        $nextProcessedTrialBalance = $this->trialBalanceRepository->findOneNext($trialBalance);

        if (null != $nextProcessedTrialBalance) {
            $nextProcessedTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
            $dm->persist($nextProcessedTrialBalance);
            $this->computeChangeSet($dm, $nextProcessedTrialBalance);
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param StoreProduct $storeProduct
     * @param DocumentManager $dm
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    protected function processSupportsRangeIndexUpdate(
        TrialBalance $trialBalance,
        StoreProduct $storeProduct,
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
            }
        }

        $trialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
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

        // :TODO: :XXX: :FIXME: Something wrong here, TrialBalance should be found
        if (!$trialBalance) {
            return;
        }

        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);

        if ($this->costOfGoodsCalculator->supportsRangeIndex($document)) {
            $this->processSupportsRangeIndexUpdate($trialBalance, $storeProduct, $dm);
        }

        $trialBalance->price = $document->getProductPrice();
        $trialBalance->quantity = $document->getProductQuantity();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->createdDate = clone $document->getReasonDate();

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
            $this->processSupportsRangeIndexRemove($trialBalance, $dm);
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

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (0 == $this->postFlushCounter++ && !$this->trialBalanceQueue->isEmpty()) {
            $dm = $eventArgs->getDocumentManager();
            foreach ($this->trialBalanceQueue as $trialBalance) {
                $dm->persist($trialBalance);
            }
            $dm->flush();
        }
        $this->postFlushCounter--;
    }
}
