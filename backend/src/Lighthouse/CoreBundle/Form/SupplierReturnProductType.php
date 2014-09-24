<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnProduct;
use Lighthouse\CoreBundle\Form\StockMovement\StockMovementProductType;

class SupplierReturnProductType extends StockMovementProductType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SupplierReturnProduct::getClassName();
    }
}
