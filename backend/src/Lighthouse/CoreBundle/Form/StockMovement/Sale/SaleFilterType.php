<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleFilter;
use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\CoreBundle\Form\FormExtraDataListener;
use Symfony\Component\Form\FormBuilderInterface;

class SaleFilterType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', 'datetime')
            ->add('dateTo', 'datetime')
            ->add(
                'product',
                'reference',
                array(
                    'class' => Product::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.stock_movement.product.does_not_exists'
                )
            )
        ;

        $builder->addEventSubscriber(new FormExtraDataListener());
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SaleFilter::getClassName();
    }
}
