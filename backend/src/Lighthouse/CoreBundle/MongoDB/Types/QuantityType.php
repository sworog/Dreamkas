<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Lighthouse\CoreBundle\Types\Numeric\Quantity;

class QuantityType extends BaseType
{
    const NAME = 'quantity';

    /**
     * @param Quantity $value
     * @return array|mixed
     */
    public static function convertToMongo($value)
    {
        if ($value instanceof Quantity) {
            return array(
                'count' => $value->getCount(),
                'precision' => $value->getPrecision()
            );
        } else {
            return null;
        }
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
