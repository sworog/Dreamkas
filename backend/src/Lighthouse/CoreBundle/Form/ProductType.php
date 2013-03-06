<?php


namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('units')
            ->add('vat')
            ->add('purchasePrice')
            ->add('barcode')
            ->add('sku')
            ->add('vendorCountry')
            ->add('vendor')
            ->add('info');
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