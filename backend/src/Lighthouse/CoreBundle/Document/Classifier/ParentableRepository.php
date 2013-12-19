<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use MongoId;

interface ParentableRepository
{
    /**
     * @param string $parentId
     * @return int
     */
    public function countByParent($parentId);

    /**
     * @param $parentId
     * @return MongoId[]
     */
    public function findIdsByParent($parentId);
}
