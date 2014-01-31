<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.document.trial_balance.calculator")
 */
class CostOfGoodsCalculator
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
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var array
     */
    protected $supportRangeIndex = array(
        InvoiceProduct::REASON_TYPE,
        SaleProduct::REASON_TYPE,
    );

    protected $supportCostOfGoods = array(
        SaleProduct::REASON_TYPE,
    );

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param NumericFactory $numericFactory
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        NumericFactory $numericFactory,
        StoreProductRepository $storeProductRepository
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->numericFactory = $numericFactory;
        $this->storeProductRepository = $storeProductRepository;
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
        $currentEndIndex = null;
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

        if ($index->toNumber() < $endIndex->toNumber()) {
            /** @var StoreProduct $storeProduct */
            $storeProduct = $this->storeProductRepository->find($storeProductId);
            $purchasePrice = $storeProduct->lastPurchasePrice?:$storeProduct->product->purchasePrice;
            $indexQuantity = $endIndex->sub($index);
            $costOfGoods = $purchasePrice->mul($indexQuantity);
            $totalCostOfGoods = $totalCostOfGoods->add($costOfGoods);
        }

        return $totalCostOfGoods;
    }

    /**
     * @param OutputInterface|null $output
     * @return void
     */
    public function calculateUnprocessed(OutputInterface $output = null)
    {
        if (null == $output) {
            $output = new NullOutput();
        }
        $dotHelper = new DotHelper($output);

        $results = $this->trialBalanceRepository->getUnprocessedTrialBalanceGroupStoreProduct();
        foreach ($results as $result) {
            $this->calculateByStoreProductId($result['_id']['storeProduct']);

            $dotHelper->write();
        }

        $dotHelper->end();
    }

    /**
     * @param string $storeProductId
     */
    public function calculateByStoreProductId($storeProductId)
    {
        foreach ($this->getSupportRangeIndex() as $reasonType) {
            $this->calculateByStoreProductReasonType($storeProductId, $reasonType);
        }
    }

    /**
     *
     * @param string $storeProductId
     * @param string $reasonType
     */
    public function calculateByStoreProductReasonType($storeProductId, $reasonType)
    {
        $trialBalance = $this->trialBalanceRepository->findOneFirstUnprocessedByStoreProductIdReasonType(
            $storeProductId,
            $reasonType
        );

        if (null != $trialBalance) {
            $this->calculateAndFixRangeIndexesByTrialBalance($trialBalance);
        }
    }

    /**
     * @return array
     */
    public function getSupportRangeIndex()
    {
        return $this->supportRangeIndex;
    }

    /**
     * @return array
     */
    public function getSupportCostOfGoods()
    {
        return $this->supportCostOfGoods;
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    public function supportsRangeIndexByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->supportsRangeIndex($trialBalance->reason);
    }

    /**
     * @param Reasonable $reason
     * @return bool
     */
    public function supportsRangeIndex(Reasonable $reason)
    {
        return in_array(
            $reason->getReasonType(),
            $this->getSupportRangeIndex()
        );
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    public function supportsCostOfGoodsByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->supportsCostOfGoods($trialBalance->reason);
    }

    public function supportsCostOfGoods(Reasonable $reason)
    {
        return in_array(
            $reason->getReasonType(),
            $this->getSupportCostOfGoods()
        );
    }

    /**
     * @param TrialBalance $trialBalance
     * @param int $batch
     */
    protected function calculateAndFixRangeIndexesByTrialBalance(TrialBalance $trialBalance, $batch = 1000)
    {
        if ($this->supportsRangeIndexByTrialBalance($trialBalance)) {
            $allNeedRecalculateTrialBalance = $this
                ->trialBalanceRepository
                ->findByStartTrialBalanceDateStoreProductReasonType($trialBalance);
            $count = 0;
            $dm = $this->trialBalanceRepository->getDocumentManager();

            $previousTrialBalance = $this->trialBalanceRepository->findOnePreviousDate($trialBalance);
            if (null != $previousTrialBalance) {
                $previousQuantity = $previousTrialBalance->endIndex;
            } else {
                $previousQuantity = $this->numericFactory->createQuantity(0);
            }

            if ($trialBalance->reason->increaseAmount()) {
                $referenceTrialBalance = $this->trialBalanceRepository->findOneByPreviousEndIndex(
                    $this->getSupportCostOfGoods(),
                    $trialBalance->storeProduct->id,
                    $previousQuantity
                );
                if (null != $referenceTrialBalance) {
                    $referenceTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                    $dm->persist($referenceTrialBalance);
                }
            }

            foreach ($allNeedRecalculateTrialBalance as $nextTrialBalance) {
                /** @var TrialBalance $nextTrialBalance */
                $nextTrialBalance->startIndex = $previousQuantity;
                $nextTrialBalance->endIndex = $nextTrialBalance->startIndex->add($nextTrialBalance->quantity);

                $this->calculateCostOfGoodsIfNeeded($nextTrialBalance);

                $nextTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_OK;
                $previousQuantity = $nextTrialBalance->endIndex;
                $dm->persist($nextTrialBalance);
                $count++;
                if (1 == $batch / $count) {
                    $count = 0;
                    $dm->flush();
                    $dm->clear();
                }
            }

            $dm->flush();
            $dm->clear();
        }
    }

    /**
     * @param TrialBalance $trialBalance
     */
    protected function calculateCostOfGoodsIfNeeded(TrialBalance $trialBalance)
    {
        if ($this->supportsCostOfGoodsByTrialBalance($trialBalance)) {
            $trialBalance->costOfGoods = $this->calculateByTrialBalance($trialBalance);
        }
    }
}
