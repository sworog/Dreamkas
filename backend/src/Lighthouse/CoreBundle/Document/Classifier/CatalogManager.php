<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Category\CategoryRepository;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;

/**
 * @DI\Service("lighthouse.core.document.catalog.manager")
 */
class CatalogManager
{
    const DEFAULT_NAME = 'default';

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @DI\InjectParams({
     *      "groupRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.group"),
     *      "categoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.category")
     * })
     * @param GroupRepository $groupRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(GroupRepository $groupRepository, CategoryRepository $categoryRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Group
     */
    public function getDefaultGroup()
    {
        $group = $this->groupRepository->findOneByName(self::DEFAULT_NAME);
        if (null === $group) {
            $group = $this->createDefaultGroup();
        }
        return $group;
    }

    /**
     * @return Group
     */
    public function createDefaultGroup()
    {
        $group = new Group();
        $group->name = self::DEFAULT_NAME;

        $this->groupRepository->save($group);

        return $group;
    }

    /**
     * @return Category
     */
    public function getDefaultCategory()
    {
        $group = $this->getDefaultGroup();
        $category = $this->categoryRepository->findOneByName(self::DEFAULT_NAME, $group->id);
        if (null === $category) {
            $category = $this->createDefaultCategory($group);
        }
        return $category;
    }

    /**
     * @param Group $group
     * @return Category
     */
    public function createDefaultCategory(Group $group)
    {
        $category = new Category();
        $category->name = self::DEFAULT_NAME;
        $category->group = $group;

        $this->categoryRepository->save($category);

        return $category;
    }

    /**
     * @return SubCategory
     */
    public function createNewCatalogGroup()
    {
        $catalogGroup = new SubCategory();
        $catalogGroup->category = $this->getDefaultCategory();

        return $catalogGroup;
    }
}
