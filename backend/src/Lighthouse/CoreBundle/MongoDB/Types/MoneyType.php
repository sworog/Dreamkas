<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Lighthouse\CoreBundle\Types\Numeric\Money;

class MoneyType extends BaseType
{
    const NAME = 'money';

    /**
     * @param Money $value
     * @return int
     */
    public static function convertToMongo($value)
    {
        return $value->getCount();
    }

    /**
     * @param int $value
     * @return Money
     */
    public static function convertToPHP($value)
    {
        return new Money($value);
    }
}
