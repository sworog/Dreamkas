<?php

namespace Lighthouse\CoreBundle\Form\Classifier;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogGroupType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SubCategory::getClassName();
    }
}
