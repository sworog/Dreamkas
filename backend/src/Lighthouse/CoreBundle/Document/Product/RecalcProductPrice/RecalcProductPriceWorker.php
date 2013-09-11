<?php

namespace Lighthouse\CoreBundle\Document\Product\RecalcProductPrice;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Job\Worker\WorkerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;

/**
 * @DI\Service("lighthouse.core.job.retail_product_price.worker")
 * @DI\Tag("job.worker")
 */
class RecalcProductPriceWorker implements WorkerInterface
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(StoreProductRepository $storeProductRepository)
    {
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        if ($job instanceof RecalcProductPriceJob) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \Lighthouse\CoreBundle\Document\Job\Job|RecalcProductPriceJob $job
     * @return mixed
     */
    public function work(Job $job)
    {
        $productVersion = $job->productVersion;
        $product = $productVersion->getObject();
        $storeProducts = $this->storeProductRepository->findByProduct($product);
        $dm = $this->storeProductRepository->getDocumentManager();
        foreach ($storeProducts as $storeProduct) {
            $this->storeProductRepository->updateRetailPriceByProduct($storeProduct, $productVersion);
            $dm->persist($storeProduct);
        }
        $dm->flush();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'recalc_product_price';
    }
}
