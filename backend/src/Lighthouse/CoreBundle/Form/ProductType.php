<?php


namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product;
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
            ->add('retailPrice', 'money')
            ->add('retailMarkup', 'markup')
            ->add('retailPricePreference', 'choice', array('choices' => Product::$retailPricePreferences));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\CoreBundle\Document\Product',
                'csrf_protection' => false
            )
        );
    }

    public function getName()
    {
        return 'product';
    }
}
