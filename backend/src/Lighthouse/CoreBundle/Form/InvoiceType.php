<?php

namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', 'text')
            ->add('acceptanceDate', 'text')
            ->add('accepter', 'text')
            ->add('legalEntity', 'text')
            ->add('supplierReferenceNumber', 'text')
            ->add('createdDate', 'text')
            ->add('sumTotal', 'text');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\\CoreBundle\\Document\\Invoice',
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }
}
