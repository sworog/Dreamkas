<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use MongoId;

abstract class ClassifierRepository extends DocumentRepository
{
    /**
     * @return mixed
     */
    abstract protected function getParentFieldName();

    /**
     * @param string $parentId
     * @return int
     */
    public function countByParent($parentId)
    {
        return $this->findByParent($parentId)->count();
    }

    /**
     * @param string $parentId
     * @return Cursor|AbstractNode[]
     */
    public function findByParent($parentId)
    {
        return $this->findBy(array($this->getParentFieldName() => $parentId));
    }

    /**
     * @param string $parentId
     * @return MongoId[]
     */
    public function findIdsByParentId($parentId)
    {
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('_id')
            ->field($this->getParentFieldName())->equals($parentId);

        $result = $qb->getQuery()->execute();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['_id'];
        }
        return $ids;
    }

    /**
     * @param string $name
     * @param string $parentId
     * @return AbstractNode
     */
    public function findOneByName($name, $parentId)
    {
        return $this->findOneBy(
            array(
                'name' => $name,
                $this->getParentFieldName() => $parentId
            )
        );
    }
}
