<?php

namespace Lighthouse\CoreBundle\Form\User;

use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class InvoiceType
 * @DI\Service("lighthouse_core.form.user")
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text')
            ->add('name', 'text')
            ->add(
                'roles',
                'choice',
                array(
                    'choices' => array(
                        User::ROLE_COMMERCIAL_MANAGER => User::ROLE_COMMERCIAL_MANAGER,
                        User::ROLE_STORE_MANAGER => User::ROLE_STORE_MANAGER,
                        User::ROLE_DEPARTMENT_MANAGER => User::ROLE_DEPARTMENT_MANAGER,
                        User::ROLE_ADMINISTRATOR => User::ROLE_ADMINISTRATOR,
                    ),
                    'multiple' => true
                )
            )
            ->add('password', 'password')
            ->add('position', 'text');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => User::getClassName(),
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
