<?php

namespace Lighthouse\CoreBundle\Document\Category;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class CategoryRepository extends DocumentRepository
{
    /**
     * @param string $klassId
     * @return int
     */
    public function countByKlass($klassId)
    {
        $query = $this
            ->createQueryBuilder()
            ->field('klass')->equals($klassId)
            ->count()
            ->getQuery();
        $count = $query->execute();
        return $count;
    }
}
