<?php

namespace Lighthouse\CoreBundle\Document\Payment;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use JMS\Serializer\Annotation as Serializer;

abstract class Payment extends AbstractDocument
{
    const TYPE = 'abstract';

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("type")
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    public function calculate(Sale $sale)
    {
    }
}
