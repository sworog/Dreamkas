<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Lighthouse\CoreBundle\Document\Classifier\ParentableClassifierRepository;

class CategoryRepository extends ParentableClassifierRepository
{
    /**
     * @return string
     */
    protected function getParentFieldName()
    {
        return 'group';
    }
}
