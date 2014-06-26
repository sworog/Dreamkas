<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Job\Worker\WorkerInterface;

/**
 * @DI\Service("lighthouse.core.job.trial_balance_process.worker")
 * @DI\Tag("job.worker")
 */
class TrialBalanceProcessWorker implements WorkerInterface
{
    /**
     * @var TrialBalanceManager
     */
    protected $trialBalanceManager;

    /**
     * @DI\InjectParams({
     *      "trialBalanceManager" = @DI\Inject("lighthouse.core.document.trial_balance.manager")
     * })
     * @param TrialBalanceManager $trialBalanceManager
     */
    public function __construct(TrialBalanceManager $trialBalanceManager)
    {
        $this->trialBalanceManager = $trialBalanceManager;
    }

    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        return $job instanceof TrialBalanceProcessJob;
    }

    /**
     * @param TrialBalanceProcessJob|Job $job
     * @return mixed result of work
     */
    public function work(Job $job)
    {
        $this->trialBalanceManager->trialBalanceJobProcess($job);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'trial_balance_process';
    }
}
