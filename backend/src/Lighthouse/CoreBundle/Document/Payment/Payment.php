<?php

namespace Lighthouse\CoreBundle\Document\Payment;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property Sale $sale
 */
abstract class Payment extends AbstractDocument
{
    const TYPE = 'abstract';

    /**
     * @var Sale
     */
    protected $sale;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("type")
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @param Sale $sale
     */
    public function calculate(Sale $sale)
    {
    }
}
