<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;

class WriteOffProductType extends StockMovementProductType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return WriteOffProduct::getClassName();
    }
}
