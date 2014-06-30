<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\Form\FormBuilderInterface;

class StoreType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'text')
            ->add('address', 'text')
            ->add('contacts', 'text')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Store::getClassName();
    }
}
