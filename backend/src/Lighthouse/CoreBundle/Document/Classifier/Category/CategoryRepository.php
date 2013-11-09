<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Doctrine\MongoDB\Cursor;
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

    /**
     * @param string $groupId
     * @return Cursor
     */
    public function findByGroup($groupId)
    {
        return $this->findBy(array('group' => $groupId));
    }
}
