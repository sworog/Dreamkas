<?php

namespace Lighthouse\CoreBundle\Types\MongoDB;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;
use Lighthouse\CoreBundle\Types\Money;

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

    public function closureToPHP()
    {
        return '$return = new Money($value);';
    }
}