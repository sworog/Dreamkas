<?php

namespace Lighthouse\CoreBundle\Document\Job\RecalcProductPrice;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Document\Job\WorkerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.job.retail_product_price.worker")
 * @DI\Tag("job.worker")
 */
class RecalcProductPriceWorker implements WorkerInterface
{
    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        if ($job instanceof RecalcProductPriceWorker) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Job|RecalcProductPriceJob $job
     * @return mixed
     */
    public function work(Job $job)
    {
        $productVersion = $job->productVersion;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'recalc_product_price';
    }
}
