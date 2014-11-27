<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnProduct;

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
