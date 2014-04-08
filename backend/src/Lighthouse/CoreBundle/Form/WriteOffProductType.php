<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WriteOffProductType extends AbstractType
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
            ->add('cause', 'text');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => WriteOffProduct::getClassName(),
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
