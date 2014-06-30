<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Symfony\Component\Form\FormBuilderInterface;

class InvoiceProductType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', array('mapped' => false))
            ->add(
                'product',
                'reference',
                array(
                    'class' => ProductVersion::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.invoice_product.product.does_not_exists'
                )
            )
            ->add('priceEntered', 'money')
            ->add('quantity', 'quantity')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return InvoiceProduct::getClassName();
    }
}
