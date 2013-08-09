<?php

namespace Lighthouse\CoreBundle\Document\Product;

use JMS\DiExtraBundle\Annotation as DI;
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
     * @DI\InjectParams({
     *      "repository" = @DI\Inject("lighthouse.core.document.repository.product")
     * })
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StoreProduct $object
     */
    public function initialize($object)
    {
        if ($object instanceof Product && !$object instanceof VersionInterface) {
            $this->repository->updateRetails($object);
        }
    }
}
