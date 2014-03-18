<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Symfony\Component\Form\FormBuilderInterface;

class SubCategoryType extends ClassifierNodeType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'category',
                'reference',
                array(
                    'class' => Category::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.subCategory.category.does_not_exists'
                )
            );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    protected function getDataClass()
    {
        return SubCategory::getClassName();
    }
}
