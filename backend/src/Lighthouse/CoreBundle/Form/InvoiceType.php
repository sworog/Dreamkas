<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Symfony\Component\Form\AbstractType;
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
            ->add('includesVAT', 'checkbox')
            ->add(
                'supplierInvoiceDate',
                'datetime',
                array('invalid_message' => 'lighthouse.validation.errors.date.invalid_value')
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
                'data_class' => Invoice::getClassName(),
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
