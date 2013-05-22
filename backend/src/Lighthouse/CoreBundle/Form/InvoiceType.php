<?php

namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class InvoiceType
 * @DI\Service("lighthouse_core.form.invoice")
 */
class InvoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sku', 'text')
            ->add('supplier', 'text')
            ->add('acceptanceDate', 'datetime')
            ->add('accepter', 'text')
            ->add('legalEntity', 'text')
            ->add('supplierInvoiceSku', 'text')
            ->add(
                'supplierInvoiceDate',
                'datetime',
                array('invalid_message' => 'lighthouse.validation.errors.date.invalid_value')
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\\CoreBundle\\Document\\Invoice\\Invoice',
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }
}
