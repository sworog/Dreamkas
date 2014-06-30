<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Order\Product\OrderProduct;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Symfony\Component\Form\FormBuilderInterface;

class OrderProductType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', array('mapped' => false))
            ->add(
                'product',
                'reference',
                array(
                    'class' => ProductVersion::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.order_product.product.does_not_exists'
                )
            )
            ->add('quantity', 'quantity')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return OrderProduct::getClassName();
    }
}
