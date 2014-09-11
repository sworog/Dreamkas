<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale;

use Lighthouse\CoreBundle\Document\Payment\BankCardPayment;
use Lighthouse\CoreBundle\Document\Payment\CashPayment;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTypeField($builder, array_keys(self::getTypeClasses()));

        switch ($options['data_class']) {
            case CashPayment::getClassName():
                $builder->add('amountTendered', 'money');
                break;
            case BankCardPayment::getClassName():
                break;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     */
    protected function addTypeField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(
                'type',
                'text',
                array(
                    'mapped' => false,
                    'constraints' => array(
                        new NotBlank(),
                        new Choice(
                            array(
                                'choices' => $choices
                            )
                        )
                    ),
                )
            );
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @param FormEvent $event
     */
    public static function addPaymentType(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $type = isset($data['payment']['type']) ? $data['payment']['type'] : null;

        /* @var Sale $sale */
        $sale = $form->getData();

        $typeClasses = self::getTypeClasses();
        if (isset($typeClasses[$type])) {
            $paymentClass = $typeClasses[$type];
            if (!$sale->payment instanceof $paymentClass) {
                $sale->payment = new $paymentClass;
            }
            $options = array('data_class' => $paymentClass);
        } else {
            $options = array('empty_data' => null);
        }

        $form->add(
            'payment',
            new static,
            $options
        );
    }

    /**
     * @return array
     */
    protected static function getTypeClasses()
    {
        return array(
            CashPayment::TYPE => CashPayment::getClassName(),
            BankCardPayment::TYPE => BankCardPayment::getClassName(),
        );
    }
}
