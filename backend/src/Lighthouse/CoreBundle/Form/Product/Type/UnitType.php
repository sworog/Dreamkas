<?php

namespace Lighthouse\CoreBundle\Form\Product\Type;

use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType as Unit;

class UnitType extends DocumentType
{
    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Unit::getClassName();
    }
}
