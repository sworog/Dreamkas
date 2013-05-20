<?php

namespace Lighthouse\CoreBundle\Form;

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
                    'class' => 'Lighthouse\\CoreBundle\\Document\\Product\\Product',
                    'invalid_message' => 'lighthouse.validation.errors.invoice_product.product.does_not_exists'
                )
            )
            ->add('price', 'money')
            ->add('quantity', 'text')
            ->add('cause', 'text');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\\CoreBundle\\Document\\WriteOff\\Product\\WriteOffProduct',
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'writeOffProduct';
    }
}
