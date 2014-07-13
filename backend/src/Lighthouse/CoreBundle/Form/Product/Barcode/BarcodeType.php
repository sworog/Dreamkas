<?php

namespace Lighthouse\CoreBundle\Form\Product\Barcode;

use Lighthouse\CoreBundle\Document\Product\Barcode\Barcode;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class BarcodeType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('barcode', 'text')
            ->add('quantity', 'quantity')
            ->add('price', 'money')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Barcode::getClassName();
    }
}
