<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class OrderType
 * @DI\Service("lighthouse_core.form.order")
 */
class OrderType extends AbstractType
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
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Order::getClassName(),
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
