<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Symfony\Component\Form\FormBuilderInterface;

class StoreProductType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'retailPrice',
                'money'
            )
            ->add(
                'retailMarkup',
                'markup',
                array('invalid_message' => 'lighthouse.validation.errors.store_product.retail_markup.invalid')
            )
            ->add(
                'retailPricePreference',
                'choice',
                array('choices' => Product::$retailPricePreferences)
            );
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return StoreProduct::getClassName();
    }
}
