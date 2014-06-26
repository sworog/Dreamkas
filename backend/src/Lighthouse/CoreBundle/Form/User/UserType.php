<?php

namespace Lighthouse\CoreBundle\Form\User;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class InvoiceType
 * @DI\Service("lighthouse_core.form.user")
 */
class UserType extends DocumentType
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
     * @return string
     */
    protected function getDataClass()
    {
        return User::getClassName();
    }
}
