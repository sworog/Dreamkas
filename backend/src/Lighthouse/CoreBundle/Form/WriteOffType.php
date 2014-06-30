<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Symfony\Component\Form\FormBuilderInterface;

class WriteOffType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'text')
            ->add('date', 'datetime')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return WriteOff::getClassName();
    }
}
