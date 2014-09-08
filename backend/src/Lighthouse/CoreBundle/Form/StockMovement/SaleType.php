<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class SaleType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime')
            ->add('amountTendered', 'money')
            ->add('paymentType', 'text')
            ->add(
                'products',
                'collection',
                array(
                    'type' => new SaleProductType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Sale::getClassName();
    }
}
