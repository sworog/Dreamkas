<?php

namespace Lighthouse\CoreBundle\Service;

use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.service.average_price")
 */
class AveragePriceService
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
     *     "storeProductRepository"=@DI\Inject("lighthouse.core.document.repository.store_product"),
     *     "trialBalanceRepository"=@DI\Inject("lighthouse.core.document.repository.trial_balance")
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

    public function recalculateAveragePrice()
    {
        $this->storeProductRepository->setAllAveragePurchasePriceToNotCalculate();

        $results = $this->trialBalanceRepository->calculateAveragePurchasePrice();
        foreach ($results as $result) {
            $this->storeProductRepository->updateAveragePurchasePrice($result['_id'], $result['value']['averagePrice']);
        }

        $this->storeProductRepository->resetAveragePurchasePriceNotCalculate();
    }
}
