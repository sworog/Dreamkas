<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class CategoryRepository extends DocumentRepository
{
    /**
     * @param string $groupId
     * @return int
     */
    public function countByGroup($groupId)
    {
        $query = $this
            ->createQueryBuilder()
            ->field('group')->equals($groupId)
            ->count()
            ->getQuery();
        $count = $query->execute();
        return $count;
    }
}
