<?php

namespace Lighthouse\CoreBundle\Form;

class GroupType extends ClassifierNodeType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return 'Lighthouse\\CoreBundle\\Document\\Classifier\\Group\\Group';
    }
}
