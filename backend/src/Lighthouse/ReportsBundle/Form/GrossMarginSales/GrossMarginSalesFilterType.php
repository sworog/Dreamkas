<?php

namespace Lighthouse\ReportsBundle\Form\GrossMarginSales;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
use Symfony\Component\Form\FormBuilderInterface;
use DateTime;

class GrossMarginSalesFilterType extends DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'dateFrom',
                'datetime',
                array(
                    'empty_data' => date('c', strtotime('-1 week 00:00:00')),
                )
            )
            ->add(
                'dateTo',
                'datetime',
                array(
                    'empty_data' => date('c', strtotime('now')),
                )
            )
            ->add(
                'store',
                'reference',
                array(
                    'class' => Store::getClassName(),
                )
            )
            ->setMethod('GET')
        ;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return GrossMarginSalesFilter::getClassName();
    }
}
