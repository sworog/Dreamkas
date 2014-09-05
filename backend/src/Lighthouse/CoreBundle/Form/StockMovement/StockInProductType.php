<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\Product\StockInProduct;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

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
