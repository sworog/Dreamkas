<?php

namespace Lighthouse\CoreBundle\Document\Group;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class GroupRepository extends DocumentRepository
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
