<?php

namespace Lighthouse\CoreBundle\Document\SubCategory;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class SubCategoryRepository extends DocumentRepository
{
    /**
     * @param string $categoryId
     * @return int
     */
    public function countByCategory($categoryId)
    {
        $query = $this
            ->createQueryBuilder()
            ->field('category')->equals($categoryId)
            ->count()
            ->getQuery();
        $count = $query->execute();
        return $count;
    }
}
