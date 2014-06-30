<?php

namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

abstract class ClassifierNodeType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('retailMarkupMin', 'markup')
            ->add('retailMarkupMax', 'markup')
            ->add('rounding', 'custom_reference', array('reference_provider_alias' => 'rounding'))
        ;
    }
}
