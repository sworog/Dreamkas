<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.service.product.metrics_calculator")
 */
class StoreProductMetricsCalculator
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @DI\InjectParams({
     *     "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *     "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance")
     * })
     *
     * @param StoreProductRepository $storeProductRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function __construct(
        StoreProductRepository $storeProductRepository,
        TrialBalanceRepository $trialBalanceRepository
    ) {
        $this->storeProductRepository = $storeProductRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @return int
     */
    public function recalculateAveragePrice()
    {
        $this->storeProductRepository->setAllAveragePurchasePriceToNotCalculate();

        $results = $this->trialBalanceRepository->calculateAveragePurchasePrice();
        foreach ($results as $result) {
            $this->storeProductRepository->updateAveragePurchasePrice(
                $result['_id'],
                $result['value']['averagePrice']
            );
        }

        $this->storeProductRepository->resetAveragePurchasePriceNotCalculate();

        return count($results);
    }

    /**
     * @return int
     */
    public function recalculateDailyAverageSales()
    {
        $this->storeProductRepository->setFieldToNotCalculate('dailyAverageSales', 0);
        $results = $this->trialBalanceRepository->calculateDailyAverageSales();
        foreach ($results as $result) {
            $this->storeProductRepository->updateAverageDailySales(
                $result['_id'],
                $result['value']['dailyAverageSales']
            );
        }
        $this->storeProductRepository->resetFieldNotCalculate('dailyAverageSales', 0);

        return count($results);
    }
}
