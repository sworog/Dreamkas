<?php

namespace Lighthouse\CoreBundle\Document\Job\RecalcProductPrice;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property ProductVersion $productVersion
 *
 * @MongoDB\Document
 */
class RecalcProductPriceJob extends Job
{
    const TYPE = 'recalc_product_price';

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var ProductVersion
     */
    protected $productVersion;

    /**
     * @return string
     */
    public function getType()
    {
        return RecalcProductPriceJob::TYPE;
    }
}
