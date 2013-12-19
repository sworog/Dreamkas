<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Lighthouse\CoreBundle\Document\Classifier\ClassifierRepository;

class CategoryRepository extends ClassifierRepository
{
    /**
     * @return string
     */
    protected function getParentFieldName()
    {
        return 'group';
    }
}
