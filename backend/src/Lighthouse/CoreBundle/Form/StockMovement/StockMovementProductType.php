<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class StockMovementProductType extends DocumentType
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
                    'invalid_message' => 'lighthouse.validation.errors.stock_movement.product.does_not_exists'
                )
            )
            ->add('price', 'money')
            ->add('quantity', 'quantity')
        ;
    }
}
