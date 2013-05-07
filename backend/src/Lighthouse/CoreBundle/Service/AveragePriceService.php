<?php

namespace Lighthouse\CoreBundle\Service;

use Lighthouse\CoreBundle\Document\ProductRepository;
use Lighthouse\CoreBundle\Document\TrialBalanceRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.service.average_price")
 */
class AveragePriceService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @DI\InjectParams({
     *     "productRepository"=@DI\Inject("lighthouse.core.document.repository.product"),
     *     "trialBalanceRepository"=@DI\Inject("lighthouse.core.document.repository.trial_balance")
     * })
     *
     * @param ProductRepository $productRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        TrialBalanceRepository $trialBalanceRepository
    ) {
        $this->productRepository = $productRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    public function recalculateAveragePrice()
    {
        $this->productRepository->setAllAveragePurchasePriceToNotCalculate();

        $results = $this->trialBalanceRepository->calculateAveragePurchasePrice();
        foreach ($results as $result) {
            $this->productRepository->updateAveragePurchasePrice($result['_id'], $result['value']['averagePrice']);
        }

        $this->productRepository->resetAveragePurchasePriceNotCalculate();
    }
}
