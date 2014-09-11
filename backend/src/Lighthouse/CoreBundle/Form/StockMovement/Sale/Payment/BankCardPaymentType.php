<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale\Payment;

use Lighthouse\CoreBundle\Document\Payment\BankCardPayment;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class BankCardPaymentType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'text', array('mapped' => false))
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return BankCardPayment::getClassName();
    }
}
