<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product\SupplierReturnProduct;
use Symfony\Component\Form\FormBuilderInterface;

class SupplierReturnProductType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'product',
                'reference',
                array(
                    'class' => ProductVersion::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.invoice_product.product.does_not_exists'
                )
            )
            ->add('price', 'money')
            ->add('quantity', 'quantity')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SupplierReturnProduct::getClassName();
    }
}
