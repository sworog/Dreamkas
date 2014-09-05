<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product\SupplierReturnProduct;
use Lighthouse\CoreBundle\Form\StockMovement\StockMovementProductType;
use Symfony\Component\Form\FormBuilderInterface;

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
