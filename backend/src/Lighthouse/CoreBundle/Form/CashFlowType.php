<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Symfony\Component\Form\FormBuilderInterface;

class CashFlowType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('direction')
            ->add('date', 'datetime')
            ->add('amount', 'money')
            ->add('comment', 'text');
    }


    /**
     * @return string
     */
    protected function getDataClass()
    {
        return CashFlow::getClassName();
    }
}
