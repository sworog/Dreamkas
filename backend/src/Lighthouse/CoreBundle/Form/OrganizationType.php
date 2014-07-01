<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Organization\Organization;
use Lighthouse\CoreBundle\Form\LegalDetailsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('phone', 'text')
            ->add('fax', 'text')
            ->add('email', 'text')
            ->add('director', 'text')
            ->add('chiefAccountant', 'text')
            ->add('address', 'text')
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array(LegalDetailsType::getClassName(), 'setTypeForm'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Organization::getClassName(),
                'csrf_protection' => false,
                'cascade_validation' => true
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
