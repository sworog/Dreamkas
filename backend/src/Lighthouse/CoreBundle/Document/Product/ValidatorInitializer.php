<?php

namespace Lighthouse\CoreBundle\Document\Product;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use Lighthouse\CoreBundle\Versionable\VersionInterface;
use Symfony\Component\Validator\ObjectInitializerInterface;

/**
 * @DI\Service("lighthouse.core.document.product.document_initializer")
 * @DI\Tag("validator.initializer")
 */
class ValidatorInitializer implements ObjectInitializerInterface
{
    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * @var RoundingManager
     */
    protected $roundingManager;

    /**
     * @DI\InjectParams({
     *      "repository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "roundingManager" = @DI\Inject("lighthouse.core.rounding.manager")
     * })
     * @param ProductRepository $repository
     * @param RoundingManager $roundingManager
     */
    public function __construct(ProductRepository $repository, RoundingManager $roundingManager)
    {
        $this->repository = $repository;
        $this->roundingManager = $roundingManager;
    }

    /**
     * @param StoreProduct $object
     */
    public function initialize($object)
    {
        if ($object instanceof Product && !$object instanceof VersionInterface) {
            $this->initRounding($object);
            $this->updateRetails($object);
        }
    }

    /**
     * @param Product $product
     */
    protected function updateRetails(Product $product)
    {
        $this->repository->updateRetails($product);
    }

    /**
     * @param Product $product
     */
    protected function initRounding(Product $product)
    {
        if (null === $product->rounding) {
            $rounding = $product->subCategory->rounding ?: $rounding = $this->roundingManager->findDefault();
            $product->setRounding($rounding);
        }
    }
}
