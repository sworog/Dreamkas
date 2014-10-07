<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale;

use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime')
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

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array(PaymentType::getClassName(), 'addPaymentType'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'cascade_validation' => true
            )
        );
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Sale::getClassName();
    }
}
