<?php


namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('units', 'text')
            ->add('vat', 'text')
            ->add('purchasePrice', 'money')
            ->add('barcode', 'text')
            ->add('sku', 'text')
            ->add('vendorCountry', 'text')
            ->add('vendor', 'text')
            ->add('info', 'text')
            ->add('retailPriceMin', 'money')
            ->add('retailPriceMax', 'money')
            ->add('retailMarkupMin', 'markup')
            ->add('retailMarkupMax', 'markup')
            ->add('retailPricePreference', 'choice', array('choices' => Product::$retailPricePreferences))
            ->add(
                'subCategory',
                'reference',
                array(
                    'class' => 'Lighthouse\\CoreBundle\\Document\\Classifier\\SubCategory\\SubCategory',
                    'invalid_message' => 'lighthouse.validation.errors.product.subCategory.does_not_exists'
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\\CoreBundle\\Document\\Product\\Product',
                'csrf_protection' => false
            )
        );
    }

    public function getName()
    {
        return '';
    }
}
