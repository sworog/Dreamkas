<?php

namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
