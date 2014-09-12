<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffProduct;
use Symfony\Component\Form\FormBuilderInterface;

class WriteOffProductType extends StockMovementProductType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('cause', 'text');
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return WriteOffProduct::getClassName();
    }
}
