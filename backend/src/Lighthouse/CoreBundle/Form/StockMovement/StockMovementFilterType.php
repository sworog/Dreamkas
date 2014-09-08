<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovementFilter;
use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\CoreBundle\Form\FormExtraDataListener;
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
