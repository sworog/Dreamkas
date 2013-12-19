<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use Lighthouse\CoreBundle\Document\Classifier\ClassifierRepository;

class SubCategoryRepository extends ClassifierRepository
{
    /**
     * @return mixed
     */
    protected function getParentFieldName()
    {
        return 'category';
    }
}
