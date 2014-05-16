<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;

class GroupType extends ClassifierNodeType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Group::getClassName();
    }
}
