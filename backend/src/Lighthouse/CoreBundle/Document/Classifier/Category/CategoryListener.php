<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class CategoryListener
{
    /**
     * @var SubCategoryRepository
     */
    protected $subCategoryRepository;

    /**
     * @DI\InjectParams({
     *      "subCategoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.subcategory")
     * })
     * @param SubCategoryRepository $subCategoryRepository
     */
    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Category) {
            $this->checkCategoryHasNoSubCategories($document);
        }
    }

    /**
     * @param Category $category
     * @throws CategoryNotEmptyException
     */
    protected function checkCategoryHasNoSubCategories(Category $category)
    {
        $count = $this->subCategoryRepository->countByCategory($category->id);
        if ($count > 0) {
            throw new CategoryNotEmptyException('Category is not empty');
        }
    }
}
