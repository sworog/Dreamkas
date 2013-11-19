<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class MoneyType extends Type
{
    /**
     * @param Money $value
     * @return int
     */
    public function convertToDatabaseValue($value)
    {
        return $value->getCount();
    }

    /**
     * @param int $value
     * @return Money
     */
    public function convertToPHPValue($value)
    {
        return new Money($value);
    }

    /**
     * @return string
     */
    public function closureToPHP()
    {
        return '$return = new \\Lighthouse\\CoreBundle\\Types\\Numeric\\Money($value);';
    }

    /**
     * @return string
     */
    public function closureToMongo()
    {
        return '$return = (int) $value;';
    }
}
