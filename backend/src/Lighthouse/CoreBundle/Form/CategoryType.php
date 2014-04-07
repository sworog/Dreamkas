<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends ClassifierNodeType
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
                'group',
                'reference',
                array(
                    'class' => Group::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.category.group.does_not_exists'
                )
            );
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Category::getClassName();
    }
}
