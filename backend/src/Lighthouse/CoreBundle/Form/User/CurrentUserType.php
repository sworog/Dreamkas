<?php

namespace Lighthouse\CoreBundle\Form\User;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class CurrentUserType extends DocumentType
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
            ->add('password', 'password')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return User::getClassName();
    }
}
