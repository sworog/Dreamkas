<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @DI\Service("lighthouse.core.document.trial_balance.calculator")
 */
class CostOfGoodCalculator
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        NumericFactory $numericFactory
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param TrialBalance $trialBalance
     * @return Money
     */
    public function calculateByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->calculateByIndexRange(
            $trialBalance->storeProduct->id,
            $trialBalance->startIndex,
            $trialBalance->endIndex
        );
    }

    /**
     * @param $storeProductId
     * @param Quantity $startIndex
     * @param Quantity $endIndex
     * @return Money
     */
    public function calculateByIndexRange($storeProductId, Quantity $startIndex, Quantity $endIndex)
    {
        $invoiceProductTrials = $this->trialBalanceRepository->findByIndexRange(
            InvoiceProduct::REASON_TYPE,
            $storeProductId,
            $startIndex,
            $endIndex
        );
        $index = $startIndex;
        $totalCostOfGoods = $this->numericFactory->createMoney(0);
        foreach ($invoiceProductTrials as $invoiceProductTrial) {
            if ($endIndex->toNumber() > $invoiceProductTrial->endIndex->toNumber()) {
                $currentEndIndex = $invoiceProductTrial->endIndex;
            } else {
                $currentEndIndex = $endIndex;
            }
            $indexQuantity = $currentEndIndex->sub($index);
            $costOfGoods = $invoiceProductTrial->price->mul($indexQuantity);
            $totalCostOfGoods = $totalCostOfGoods->add($costOfGoods);
            $index = $index->add($indexQuantity);
        }
        return $totalCostOfGoods;
    }

    /**
     * @param int $limit
     * @return int number of items calculated
     */
    public function calculateUnprocessedTrialBalances($limit = 1000)
    {
        $dm = $this->trialBalanceRepository->getDocumentManager();
        $cursor = $this->trialBalanceRepository->findByProcessingStatus(
            TrialBalance::PROCESSING_STATUS_NEED_CALC_COST_OF_GOODS,
            SaleProduct::REASON_TYPE,
            $limit
        );
        $count = 0;
        foreach ($cursor as $trialBalance) {
            $trialBalance->costOfGoods = $this->calculateByTrialBalance($trialBalance);
            $trialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_OK;
            $dm->persist($trialBalance);
            $count++;
        }
        $dm->flush();
        return $count;
    }

    /**
     * @return void
     */
    public function checkAndFixRangeIndexes()
    {
        $results = $this->trialBalanceRepository->getAllFirstUnprocessedTrialBalance();
        foreach ($results as $result) {
            $this->fixRangeIndexes(
                $result['minCreatedDate'],
                $result['_id']['storeProduct'],
                $result['_id']['reasonType']
            );
        }
    }

    /**
     * @param \MongoDate $startDate
     * @param string $storeProduct
     * @param string $reasonType
     * @param int $batch
     */
    public function fixRangeIndexes($startDate, $storeProduct, $reasonType, $batch = 1000)
    {
        /** @var TrialBalance $trialBalance */
        $trialBalance = $this->trialBalanceRepository->findOneByStoreProductIdDateReasonType(
            $startDate,
            $reasonType,
            $storeProduct
        );
        if ($this->supportsRangeIndex($trialBalance)) {
            $allNeedRecalculateTrialBalance = $this->trialBalanceRepository->findBy(
                array(
                    'createdDate.date' => array('$gte' => $trialBalance->createdDate),
                    'reason.$ref' => $trialBalance->reason->getReasonType(),
                    'storeProduct' => $trialBalance->storeProduct->id
                ),
                array(
                    'createdDate.date' => 1,
                    '_id' => 1
                )
            );
            $count = 0;
            $previousQuantity = null;
            $dm = $this->trialBalanceRepository->getDocumentManager();
            foreach ($allNeedRecalculateTrialBalance as $nextTrialBalance) {
                if (null == $previousQuantity) {
                    $previousTrialBalance = $this->trialBalanceRepository->findOnePreviousDate($trialBalance);
                    if (null != $previousTrialBalance) {
                        $previousQuantity = $previousTrialBalance->endIndex;
                    } else {
                        $previousQuantity = $this->numericFactory->createQuantity(0);
                    }
                }

                /** @var TrialBalance $nextTrialBalance */
                $nextTrialBalance->startIndex = $previousQuantity;
                $nextTrialBalance->endIndex = $nextTrialBalance->startIndex->add($nextTrialBalance->quantity);
                if ($this->supportsCostOfGoods($nextTrialBalance)) {
                    $nextTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_NEED_CALC_COST_OF_GOODS;
                } else {
                    $nextTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_OK;
                }
                $previousQuantity = $nextTrialBalance->endIndex;
                $dm->persist($nextTrialBalance);
                $count++;
                if (1 == $batch / $count) {
                    $count = 0;
                    $dm->flush();
                }
            }

            $dm->flush();
        }
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

    protected function supportsCostOfGoods(TrialBalance $trialBalance)
    {
        return in_array(
            $trialBalance->reason->getReasonType(),
            array(SaleProduct::REASON_TYPE)
        );
    }
}
