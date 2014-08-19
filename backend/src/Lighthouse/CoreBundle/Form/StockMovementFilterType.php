<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovementFilter;
use Symfony\Component\Form\FormBuilderInterface;

class StockMovementFilterType extends DocumentType
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
            ->add('types', 'enum')
        ;

        $builder->addEventSubscriber(new FormExtraDataListener());
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return StockMovementFilter::getClassName();
    }
}
