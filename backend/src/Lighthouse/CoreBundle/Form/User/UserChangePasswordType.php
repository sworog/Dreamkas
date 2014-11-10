<?php

namespace Lighthouse\CoreBundle\Form\User;

use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\CoreBundle\Validator\Constraints\User\CurrentUserPassword;
use Lighthouse\CoreBundle\Validator\Constraints\User\Password;
use Symfony\Component\Form\FormBuilderInterface;

class UserChangePasswordType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'password',
                'password',
                array(
                    'constraints' => array(
                        new CurrentUserPassword()
                    )
                )
            )
            ->add(
                'newPassword',
                'repeated',
                array(
                    'type' => 'password',
                    'invalid_message' => 'lighthouse.validation.errors.user.password.not_equals_password',
                    'constraints' => array(
                        new Password()
                    )
                )
            )
            ->add(
                'email',
                'text',
                array(
                    'read_only' => true
                )
            )
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return UserChangePasswordModel::getClassName();
    }
}
