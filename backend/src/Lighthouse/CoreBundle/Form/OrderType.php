<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class OrderType
 * @DI\Service("lighthouse_core.form.order")
 */
class OrderType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'supplier',
                'reference',
                array(
                    'class' => Supplier::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.order.supplier.does_not_exists'
                )
            )
            ->add(
                'products',
                'collection',
                array(
                    'type' => new OrderProductType(),
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
        return Order::getClassName();
    }
}
