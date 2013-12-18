<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use MongoId;

class CategoryRepository extends DocumentRepository
{
    /**
     * @param string $groupId
     * @return int
     */
    public function countByGroup($groupId)
    {
        return $this->findByGroup($groupId)->count();
    }

    /**
     * @param string $groupId
     * @return Cursor|Category[]
     */
    public function findByGroup($groupId)
    {
        return $this->findBy(array('group' => $groupId));
    }

    /**
     * @param string $groupId
     * @return MongoId[]
     */
    public function findIdsByGroupId($groupId)
    {
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('_id')
            ->field('group')->equals($groupId);
        $result = $qb->getQuery()->execute();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['_id'];
        }
        return $ids;

    }

    /**
     * @param string $name
     * @param string $groupId
     * @return Category
     */
    public function findOneByName($name, $groupId)
    {
        return $this->findOneBy(array('name' => $name, 'group' => $groupId));
    }
}
