<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Symfony\Component\Form\FormBuilderInterface;

class SupplierType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add(
                'agreement',
                'reference',
                array(
                    'class' => File::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.supplier.file.does_not_exist'
                )
            )
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Supplier::getClassName();
    }
}
