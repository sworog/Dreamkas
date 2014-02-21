<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use Lighthouse\CoreBundle\Document\Classifier\ParentableClassifierRepository;

class SubCategoryRepository extends ParentableClassifierRepository
{
    /**
     * @return mixed
     */
    protected function getParentFieldName()
    {
        return 'category';
    }
}
