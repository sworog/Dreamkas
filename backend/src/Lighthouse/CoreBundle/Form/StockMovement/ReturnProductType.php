<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Product\ReturnProduct;

class ReturnProductType extends StockMovementProductType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return ReturnProduct::getClassName();
    }
}
