<?php

namespace Lighthouse\CoreBundle\Form\Product\Type;

use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;
use Lighthouse\CoreBundle\Document\Product\Type;

class WeightType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameOnScales', 'text')
            ->add('descriptionOnScales', 'text')
            ->add('ingredients', 'text')
            ->add('shelfLife', 'text')
            ->add('nutritionFacts', 'text')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Type\WeightType::getClassName();
    }
}
