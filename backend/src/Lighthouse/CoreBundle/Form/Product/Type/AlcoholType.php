<?php

namespace Lighthouse\CoreBundle\Form\Product\Type;

use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;
use Lighthouse\CoreBundle\Document\Product\Type;

class AlcoholType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alcoholByVolume', 'quantity')
            ->add('volume', 'quantity')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Type\AlcoholType::getClassName();
    }
}
