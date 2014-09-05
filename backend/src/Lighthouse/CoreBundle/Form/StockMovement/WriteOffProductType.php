<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

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
