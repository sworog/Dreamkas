<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class SubCategoryRepository extends DocumentRepository
{
    /**
     * @param string $categoryId
     * @return int
     */
    public function countByCategory($categoryId)
    {
        return $this->findByCategory($categoryId)->count();
    }

    /**
     * @param string $categoryId
     * @return Cursor|SubCategory[]
     */
    public function findByCategory($categoryId)
    {
        return $this->findBy(array('category' => $categoryId));
    }

    /**
     * @param string $name
     * @param string $categoryId
     * @return SubCategory
     */
    public function findOneByName($name, $categoryId)
    {
        return $this->findOneBy(array('name' => $name, 'category' => $categoryId));
    }
}
