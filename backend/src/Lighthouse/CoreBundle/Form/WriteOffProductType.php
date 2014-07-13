<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Symfony\Component\Form\FormBuilderInterface;

class WriteOffProductType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'product',
                'reference',
                array(
                    'class' => ProductVersion::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.invoice_product.product.does_not_exists'
                )
            )
            ->add('price', 'money')
            ->add('quantity', 'quantity')
            ->add('cause', 'text')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return WriteOffProduct::getClassName();
    }
}
