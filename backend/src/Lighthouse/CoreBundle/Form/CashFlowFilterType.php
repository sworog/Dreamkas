<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\CashFlow\CashFlowFilter;
use Lighthouse\CoreBundle\Form\Listener\FormExtraDataListener;
use Symfony\Component\Form\FormBuilderInterface;

class CashFlowFilterType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', 'datetime')
            ->add('dateTo', 'datetime')
        ;

        $builder->addEventSubscriber(new FormExtraDataListener());
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return CashFlowFilter::getClassName();
    }
}
