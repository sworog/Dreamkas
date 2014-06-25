<?php

namespace Lighthouse\CoreBundle\Document\Product\RecalcProductPrice;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Versionable\VersionFactory;

/**
 * @DI\Service("lighthouse.core.job.retail_product_price.factory")
 */
class RecalcProductPriceFactory
{
    /**
     * VersionFactory
     * @var
     */
    protected $versionFactory;

    /**
     * @DI\InjectParams({
     *      "versionFactory" = @DI\Inject("lighthouse.core.versionable.factory")
     * })
     * @param VersionFactory $versionFactory
     */
    public function __construct(VersionFactory $versionFactory)
    {
        $this->versionFactory = $versionFactory;
    }

    /**
     * @param Product $product
     * @return RecalcProductPriceJob
     */
    public function createByProduct(Product $product)
    {
        $productVersion = $this->versionFactory->createDocumentVersion($product);

        $job = new RecalcProductPriceJob();
        $job->productVersion = $productVersion;

        return $job;
    }
}
