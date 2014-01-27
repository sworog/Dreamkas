<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodCalculator;
use Lighthouse\CoreBundle\Job\Worker\WorkerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.job.cost_of_goods_calculate.worker")
 * @DI\Tag("job.worker")
 */
class CostOfGoodsWorker implements WorkerInterface
{
    /**
     * @var CostOfGoodCalculator
     */
    protected $costOfGoodCalculator;

    /**
     * @DI\InjectParams({
     *      "costOfGoodCalculator" = @DI\Inject("lighthouse.core.document.trial_balance.calculator")
     * })
     *
     * @param CostOfGoodCalculator $costOfGoodCalculator
     */
    public function __construct(CostOfGoodCalculator $costOfGoodCalculator)
    {
        $this->costOfGoodCalculator = $costOfGoodCalculator;
    }

    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        return $job instanceof CostOfGoodsCalculateJob;
    }

    /**
     * @param CostOfGoodsCalculateJob|Job $job
     * @return mixed result of work
     */
    public function work(Job $job)
    {
        $storeProductId = $job->storeProductId;
        $this->costOfGoodCalculator->calculateByStoreProductId($storeProductId);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'cost_of_goods_calculate';
    }

}