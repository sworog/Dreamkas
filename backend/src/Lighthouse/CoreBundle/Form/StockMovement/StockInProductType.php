<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInProduct;

class StockInProductType extends StockMovementProductType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return StockInProduct::getClassName();
    }
}
