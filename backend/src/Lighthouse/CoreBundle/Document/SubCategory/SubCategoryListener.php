<?php

namespace Lighthouse\CoreBundle\Document\SubCategory;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class SubCategoryListener
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @DI\InjectParams({
     *      "productRepository"=@DI\Inject("lighthouse.core.document.repository.product")
     * })
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof SubCategory) {
            $this->checkSubCategoryHasNoProducts($document);
        }
    }

    /**
     * @param SubCategory $subCategory
     * @throws SubCategoryNotEmptyException
     */
    protected function checkSubCategoryHasNoProducts(SubCategory $subCategory)
    {
        $count = $this->productRepository->countBySubCategory($subCategory->id);
        if ($count > 0) {
            throw new SubCategoryNotEmptyException('SubCategory is not empty');
        }
    }
}
