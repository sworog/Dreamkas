<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class InvoiceType
 * @DI\Service("lighthouse_core.form.invoice")
 */
class InvoiceType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'number',
                'text',
                array(
                    'mapped' => false
                )
            )
            ->add(
                'order',
                'reference',
                array(
                    'class' => Order::getClassName(),
                    'return_null_object_on_not_found' => true,
                )
            )
            ->add(
                'supplier',
                'reference',
                array(
                    'class' => Supplier::getClassName(),
                    'return_null_object_on_not_found' => true,
                    'invalid_message' => 'lighthouse.validation.errors.invoice.supplier.does_not_exists'
                )
            )
            ->add(
                'acceptanceDate',
                'datetime'
            )
            ->add(
                'accepter',
                'text'
            )
            ->add(
                'legalEntity',
                'text'
            )
            ->add(
                'supplierInvoiceNumber',
                'text'
            )
            ->add(
                'includesVAT',
                'checkbox'
            )
            ->add(
                'products',
                'collection',
                array(
                    'type' => new InvoiceProductType(),
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
        return Invoice::getClassName();
    }
}
