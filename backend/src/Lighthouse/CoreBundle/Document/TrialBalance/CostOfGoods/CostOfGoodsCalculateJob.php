<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods;

use Lighthouse\CoreBundle\Document\Job\Job;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property string $storeProductId
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Job\JobRepository",
 *      collection="Jobs"
 * )
 */
class CostOfGoodsCalculateJob extends Job
{
    const TYPE = 'cost_of_goods_calculate';

    /**
     * @MongoDB\String
     * @var string
     */
    protected $storeProductId;

    /**
     * @return string
     */
    public function getType()
    {
        return CostOfGoodsCalculateJob::TYPE;
    }
}
