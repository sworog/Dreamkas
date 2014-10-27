<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\ParentableClassifierRepository;

class SubCategoryRepository extends ParentableClassifierRepository
{
    /**
     * @return mixed
     */
    protected function getParentFieldName()
    {
        return 'category';
    }

    /**
     * @param string $parentId
     * @return Cursor|SubCategory[]
     */
    public function findByParent($parentId)
    {
        return $this->findBy(
            array($this->getParentFieldName() => $parentId, 'deletedAt' => null),
            array('name' => self::SORT_ASC)
        );
    }

    /**
     * @param Category $category
     * @return Cursor|SubCategory[]
     */
    public function findByCategory(Category $category)
    {
        return $this->findByParent($category->id);
    }
}
