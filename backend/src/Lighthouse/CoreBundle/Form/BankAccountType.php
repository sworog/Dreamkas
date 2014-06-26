<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\BankAccount\BankAccount;
use Symfony\Component\Form\FormBuilderInterface;

class BankAccountType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bic', 'text')
            ->add('bankName', 'text')
            ->add('bankAddress', 'text')
            ->add('correspondentAccount', 'text')
            ->add('account', 'text')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return BankAccount::getClassName();
    }
}
