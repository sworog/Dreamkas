<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

interface CountableByParent
{
    /**
     * @param string $parentId
     * @return int
     */
    public function countByParent($parentId);
}
