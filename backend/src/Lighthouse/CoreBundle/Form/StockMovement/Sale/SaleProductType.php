<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Sale;

use Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Form\StockMovement\StockMovementProductType;

class SaleProductType extends StockMovementProductType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SaleProduct::getClassName();
    }
}
