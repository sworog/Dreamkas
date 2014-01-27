<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Job\JobManager;

/**
 * @DI\Service("lighthouse.core.document.trial_balance.cost_of_goods.manager")
 */
class CostOfGoodsManager
{
    /**
     * @var JobManager
     */
    protected $jobManager;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @DI\InjectParams({
     *      "jobManager" = @DI\Inject(
     *          "lighthouse.core.job.manager"
     *      ),
     *      "trialBalanceRepository" = @DI\Inject(
     *          "lighthouse.core.document.repository.trial_balance"
     *      ),
     * })
     *
     * @param JobManager $jobManager
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function __construct(
        JobManager $jobManager,
        TrialBalanceRepository $trialBalanceRepository
    ) {
        $this->jobManager = $jobManager;
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    public function createCalculateJobsForUnprocessed()
    {
        $results = $this->trialBalanceRepository->getUnprocessedTrialBalanceGroupStoreProduct();
        $count = 0;
        foreach ($results as $result) {
            $this->createJobByStoreProductId($result['_id']['storeProduct']);
            $count++;
        }

        return $count;
    }

    protected function createJobByStoreProductId($storeProductId)
    {
        $job = new CostOfGoodsCalculateJob();
        $job->storeProductId = $storeProductId;
        $this->jobManager->addJob($job);
    }
}
