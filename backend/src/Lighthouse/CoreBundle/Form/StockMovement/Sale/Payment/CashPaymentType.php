<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale\Payment;

use Lighthouse\CoreBundle\Document\Payment\CashPayment;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class CashPaymentType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'text', array('mapped' => false))
            ->add('amountTendered', 'money')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return CashPayment::getClassName();
    }
}
