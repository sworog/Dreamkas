<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
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
}
