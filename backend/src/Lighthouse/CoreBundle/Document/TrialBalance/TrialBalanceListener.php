<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
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
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var SplQueue|TrialBalance[]
     */
    protected $trialBalanceQueue;

    /**
     * @var array
     */
    protected $persistedIndexes = array();

    /**
     * @var int
     */
    protected $postFlushCounter = 0;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "invoiceProductRepository" = @DI\Inject("lighthouse.core.document.repository.invoice_product"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param InvoiceProductRepository $invoiceProductRepository
     * @param StoreProductRepository $storeProductRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        InvoiceProductRepository $invoiceProductRepository,
        StoreProductRepository $storeProductRepository,
        NumericFactory $numericFactory
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->invoiceProductRepository = $invoiceProductRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->numericFactory = $numericFactory;

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

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     */
    protected function onReasonablePersist(Reasonable $document, DocumentManager $dm)
    {
        $storeProduct = $this->createStoreProduct($document, $dm);
        $this->createTrialBalance($document, $storeProduct);
    }

    /**
     * @param Reasonable $document
     * @param DocumentManager $dm
     * @return StoreProduct
     */
    protected function createStoreProduct(Reasonable $document, DocumentManager $dm)
    {
        $storeProduct = $this->storeProductRepository->findOrCreateByReason($document);

        $dm->persist($storeProduct);
        $this->computeChangeSet($dm, $storeProduct);

        return $storeProduct;
    }

    /**
     * @param Reasonable $reason
     * @param StoreProduct $storeProduct
     * @return TrialBalance
     */
    protected function createTrialBalance(Reasonable $reason, StoreProduct $storeProduct)
    {
        $trialBalance = new TrialBalance();
        $trialBalance->price = $reason->getProductPrice();
        $trialBalance->quantity = $reason->getProductQuantity();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->reason = $reason;
        $trialBalance->createdDate = $reason->getReasonDate();

        $this->trialBalanceQueue[] = $trialBalance;

        return $trialBalance;
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
                if ($this->supportsRangeIndex($trialBalance)) {
                    $this->setTrialBalanceIndexRange($trialBalance);
                }
                $dm->persist($trialBalance);
            }
            $dm->flush();
            $this->postFlushCounter--;
        }
        $this->clearPersistedIndex();
    }

    /**
     * @param TrialBalance $trialBalance
     */
    protected function setTrialBalanceIndexRange(TrialBalance $trialBalance)
    {
        $startIndex = $this->pullPersistedIndex($trialBalance);
        if (!$startIndex) {
            $previousTrialBalance = $this->trialBalanceRepository->findOnePrevious($trialBalance);
            if ($previousTrialBalance) {
                $startIndex = clone $previousTrialBalance->endIndex;
            } else {
                $startIndex = $this->numericFactory->createQuantity(0);
            }
        }

        $trialBalance->startIndex = $startIndex;
        $trialBalance->endIndex = $trialBalance->startIndex->add($trialBalance->quantity);

        $this->pushPersistedIndex($trialBalance);
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    protected function supportsRangeIndex(TrialBalance $trialBalance)
    {
        return in_array(
            $trialBalance->reason->getReasonType(),
            array(InvoiceProduct::REASON_TYPE, SaleProduct::REASON_TYPE)
        );
    }

    /**
     * @param TrialBalance $trialBalance
     */
    protected function pushPersistedIndex(TrialBalance $trialBalance)
    {
        $reasonType = $trialBalance->reason->getReasonType();
        $storeProductId = $trialBalance->storeProduct->id;
        $this->persistedIndexes[$reasonType][$storeProductId] = $trialBalance->endIndex;
    }

    /**
     * @param TrialBalance $trialBalance
     * @return Quantity
     */
    protected function pullPersistedIndex(TrialBalance $trialBalance)
    {
        $reasonType = $trialBalance->reason->getReasonType();
        $storeProductId = $trialBalance->storeProduct->id;
        if (isset($this->persistedIndexes[$reasonType][$storeProductId])) {
            $index = $this->persistedIndexes[$reasonType][$storeProductId];
            unset($this->persistedIndexes[$reasonType][$storeProductId]);
            return $index;
        } else {
            return null;
        }
    }

    protected function clearPersistedIndex()
    {
        $this->persistedIndexes = array();
    }
}
