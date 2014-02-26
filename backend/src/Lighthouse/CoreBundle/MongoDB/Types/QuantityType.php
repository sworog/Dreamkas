<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Lighthouse\CoreBundle\Types\Numeric\Quantity;

class QuantityType extends BaseType
{
    const QUANTITY = 'quantity';

    /**
     * @param Quantity $value
     * @return array|mixed
     */
    public static function convertToMongo($value)
    {
        return array('count' => $value->getCount(), 'precision' => $value->getPrecision());
    }

    /**
     * @param null|\stdClass $value
     * @return Quantity|null
     */
    public static function convertToPHP($value)
    {
        if (null !== $value && isset($value['count'], $value['precision'])) {
            return new Quantity($value['count'], $value['precision']);
        } else {
            return null;
        }
    }
}
