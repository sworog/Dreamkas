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
     * @param string $name
     * @param string $groupId
     * @return Category
     */
    public function findOneByName($name, $groupId)
    {
        return $this->findOneBy(array('name' => $name, 'group' => $groupId));
    }
}
