<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Returne;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct;
use Lighthouse\CoreBundle\Form\StockMovement\StockMovementProductType;

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
