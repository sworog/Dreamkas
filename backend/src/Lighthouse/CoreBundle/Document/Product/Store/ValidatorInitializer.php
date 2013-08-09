<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\ObjectInitializerInterface;

/**
 * @DI\Service("lighthouse.core.validator.document_initializer")
 * @DI\Tag("validator.initializer")
 */
class ValidatorInitializer implements ObjectInitializerInterface
{
    /**
     * @var StoreProductRepository
     */
    protected $repository;

    /**
     * @DI\InjectParams({
     *      "repository" = @DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     * @param StoreProductRepository $repository
     */
    public function __construct(StoreProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StoreProduct $object
     */
    public function initialize($object)
    {
        if ($object instanceof StoreProduct) {
            $this->repository->updateRetails($object);
        }
    }
}
