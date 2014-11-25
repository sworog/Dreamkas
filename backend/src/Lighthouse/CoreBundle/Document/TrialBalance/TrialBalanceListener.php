<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;

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
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "costOfGoodsCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param StoreProductRepository $storeProductRepository
     * @param CostOfGoodsCalculator $costOfGoodsCalculator
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        StoreProductRepository $storeProductRepository,
        CostOfGoodsCalculator $costOfGoodsCalculator
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->costOfGoodsCalculator = $costOfGoodsCalculator;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentInsertions() as $document) {
            if ($document instanceof StockMovementProduct) {
                $this->onStockMovementProductPersist($document, $dm);
            }
        }

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof StockMovementProduct) {
                $this->onStockMovementProductUpdate($document, $dm);
            }

            if ($document instanceof Invoice) {
                $this->processInvoiceOnAcceptanceDateUpdate($document, $dm, $uow);
            }
        }

        foreach ($uow->getScheduledDocumentDeletions() as $document) {
            if ($document instanceof StockMovementProduct) {
                $this->onStockMovementProductRemove($document, $dm);
            }
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param DocumentManager $dm
     */
    protected function processSupportsRangeIndexRemove(TrialBalance $trialBalance, DocumentManager $dm)
    {
        $nextProcessedTrialBalance = $this->trialBalanceRepository->findOneNext(
            $trialBalance,
            $this->costOfGoodsCalculator->getStockMovementTypesByTrialBalance($trialBalance)
        );

        if (null != $nextProcessedTrialBalance) {
            $oldValue = $nextProcessedTrialBalance->processingStatus;
            $nextProcessedTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;

            $this->schedulePropertyChange(
                $dm->getUnitOfWork(),
                $nextProcessedTrialBalance,
                'processingStatus',
                $oldValue,
                $nextProcessedTrialBalance->processingStatus
            );
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
            $needProcessedTrialProduct = $this->trialBalanceRepository->findOnePrevious(
                $trialBalance,
                $this->costOfGoodsCalculator->getStockMovementTypesByTrialBalance($trialBalance)
            );
            if (null == $needProcessedTrialProduct) {
                $needProcessedTrialProduct = $this->trialBalanceRepository->findOneNext(
                    $trialBalance,
                    $this->costOfGoodsCalculator->getStockMovementTypesByTrialBalance($trialBalance)
                );
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
     * @param StockMovementProduct $document
     * @param DocumentManager $dm
     */
    protected function onStockMovementProductPersist(StockMovementProduct $document, DocumentManager $dm)
    {
        $storeProduct = $this->storeProductRepository->findOrCreateByStockMovementProduct($document);
        $dm->persist($storeProduct);
        $this->computeChangeSet($dm, $storeProduct);

        $trialBalance = new TrialBalance();
        $trialBalance->price = $document->price;
        $trialBalance->quantity = $document->quantity;
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->reason = $document;
        $trialBalance->createdDate = $document->date;

        $dm->persist($trialBalance);
        $this->computeChangeSet($dm, $trialBalance);
    }
    
    /**
     * @param StockMovementProduct $document
     * @param DocumentManager $dm
     */
    protected function onStockMovementProductUpdate(StockMovementProduct $document, DocumentManager $dm)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByStockMovementProduct($document);

        // FIXME Something wrong here, TrialBalance should be found
        if (!$trialBalance) {
            return;
        }

        $storeProduct = $this->storeProductRepository->findOrCreateByStockMovementProduct($document);

        if ($this->costOfGoodsCalculator->supportsRangeIndex($document)) {
            $this->processSupportsRangeIndexUpdate($trialBalance, $storeProduct, $dm);
        }

        $trialBalance->price = $document->price;
        $trialBalance->quantity = $document->quantity;
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->createdDate = clone $document->date;

        $dm->persist($storeProduct);
        $dm->persist($trialBalance);

        $this->computeChangeSet($dm, $trialBalance);
    }

    /**
     * @param StockMovementProduct $document
     * @param DocumentManager $dm
     */
    protected function onStockMovementProductRemove(StockMovementProduct $document, DocumentManager $dm)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByStockMovementProduct($document);

        if ($this->costOfGoodsCalculator->supportsRangeIndex($document)) {
            $this->processSupportsRangeIndexRemove($trialBalance, $dm);
        }

        $dm->remove($trialBalance);
    }

    /**
     * @param Invoice $invoice
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     */
    protected function processInvoiceOnAcceptanceDateUpdate(Invoice $invoice, DocumentManager $dm, UnitOfWork $uow)
    {
        $changeSet = $uow->getDocumentChangeSet($invoice);
        if (!isset($changeSet['date'])) {
            return;
        }
        $oldAcceptanceDate = $changeSet['date'][0];
        $newAcceptanceDate = $changeSet['date'][1];

        $invoiceProducts = $invoice->products;
        $trialBalances = $this->trialBalanceRepository->findByStockMovementProducts($invoiceProducts);

        foreach ($invoiceProducts as $invoiceProduct) {
            $invoiceProduct->beforeSave();
            $this->computeChangeSet($dm, $invoiceProduct);
        }

        foreach ($trialBalances as $trialBalance) {
            if ($newAcceptanceDate > $oldAcceptanceDate) {
                $nextTrialBalance = $this->trialBalanceRepository->findOneNext(
                    $trialBalance,
                    $this->costOfGoodsCalculator->getStockMovementTypesByTrialBalance($trialBalance)
                );
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
