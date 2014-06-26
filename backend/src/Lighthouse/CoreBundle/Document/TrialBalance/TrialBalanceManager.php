<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;

/**
 * @DI\Service("lighthouse.core.document.trial_balance.manager")
 */
class TrialBalanceManager
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
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "costOfGoodsCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator"),
     *      "documentManager" = @DI\Inject("doctrine.odm.mongodb.document_manager")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param StoreProductRepository $storeProductRepository
     * @param CostOfGoodsCalculator $costOfGoodsCalculator
     * @param DocumentManager $documentManager
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        StoreProductRepository $storeProductRepository,
        CostOfGoodsCalculator $costOfGoodsCalculator,
        DocumentManager $documentManager
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->costOfGoodsCalculator = $costOfGoodsCalculator;
        $this->documentManager = $documentManager;
    }

    /**
     * @param $reasonId
     * @param $reasonType
     * @return Reasonable
     */
    public function findReasonByIdType($reasonId, $reasonType)
    {
        return $this->documentManager->find($reasonType, $reasonId);
    }

    public function flush()
    {
        $this->documentManager->flush();
    }

    /**
     * @param TrialBalanceProcessJob $job
     */
    public function trialBalanceJobProcess(TrialBalanceProcessJob $job)
    {
        switch ($job->processType) {
            case TrialBalanceProcessJob::PROCESS_TYPE_CREATE:
                $reason = $this->findReasonByIdType($job->reasonId, $job->reasonClassName);
                if (null !== $reason) {
                    $this->reasonableCreateProcess($reason);
                }
                break;

            case TrialBalanceProcessJob::PROCESS_TYPE_UPDATE:
                $reason = $this->findReasonByIdType($job->reasonId, $job->reasonClassName);
                $this->reasonableUpdateProcess($reason);
                break;

            case TrialBalanceProcessJob::PROCESS_TYPE_REMOVE:
                $this->reasonableRemoveProcess($job->reasonId, $job->reasonType);
                break;
        }

        $this->flush();
    }

    /**
     * @param Reasonable $reasonable
     */
    public function reasonableCreateProcess(Reasonable $reasonable)
    {
        $storeProduct = $this->storeProductRepository->findOrCreateByReason($reasonable);

        $trialBalance = new TrialBalance();
        $trialBalance->price = $reasonable->getProductPrice();
        $trialBalance->quantity = $reasonable->getProductQuantity();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->reason = $reasonable;
        $trialBalance->createdDate = $reasonable->getReasonDate();

        $this->documentManager->persist($trialBalance);
    }

    /**
     * @param Reasonable $reasonable
     */
    public function reasonableUpdateProcess(Reasonable $reasonable)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByReason($reasonable);

        $storeProduct = $this->storeProductRepository->findOrCreateByReason($reasonable);

        if ($this->costOfGoodsCalculator->supportsRangeIndex($reasonable)) {
            $this->processSupportsRangeIndexUpdate($trialBalance, $storeProduct);
        }

        $trialBalance->price = $reasonable->getProductPrice();
        $trialBalance->quantity = $reasonable->getProductQuantity();
        $trialBalance->storeProduct = $storeProduct;
        $trialBalance->createdDate = $reasonable->getReasonDate();

        $this->documentManager->persist($trialBalance);
    }

    /**
     * @param $reasonId
     * @param $reasonType
     */
    public function reasonableRemoveProcess($reasonId, $reasonType)
    {
        $trialBalance = $this->trialBalanceRepository->findOneByReasonTypeReasonId($reasonId, $reasonType);

        if (null != $trialBalance) {
            if ($this->costOfGoodsCalculator->supportsRangeIndexByReasonType($reasonType)) {
                $this->processSupportsRangeIndexRemove($trialBalance, $reasonType);
            }

            $this->documentManager->remove($trialBalance);
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param string $reasonType
     */
    protected function processSupportsRangeIndexRemove(TrialBalance $trialBalance, $reasonType)
    {
        $nextProcessedTrialBalance = $this->trialBalanceRepository->findOneNextByTrialBalanceReasonType(
            $trialBalance,
            $reasonType
        );

        if (null != $nextProcessedTrialBalance) {
            $nextProcessedTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
            $this->documentManager->persist($nextProcessedTrialBalance);
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param StoreProduct $storeProduct
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    protected function processSupportsRangeIndexUpdate(
        TrialBalance $trialBalance,
        StoreProduct $storeProduct
    ) {
        if ($storeProduct != $trialBalance->storeProduct) {
            $needProcessedTrialProduct = $this->trialBalanceRepository->findOnePrevious($trialBalance);
            if (null == $needProcessedTrialProduct) {
                $needProcessedTrialProduct = $this->trialBalanceRepository->findOneNext($trialBalance);
            }
            if (null != $needProcessedTrialProduct) {
                $needProcessedTrialProduct->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                $this->documentManager->persist($needProcessedTrialProduct);
            }
        }

        $trialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
    }
}
