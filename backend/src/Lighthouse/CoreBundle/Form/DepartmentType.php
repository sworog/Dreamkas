<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Department\Department;
use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\Form\FormBuilderInterface;

class DepartmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'text')
            ->add('name', 'text')
            ->add(
                'store',
                'reference',
                array(
                    'class' => Store::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.department.store.does_not_exists'
                )
            )
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Department::getClassName();
    }
}
