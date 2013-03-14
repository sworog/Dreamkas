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

    /**
     * @return string
     */
    public function closureToPHP()
    {
        return '$return = new \Lighthouse\CoreBundle\Types\Money($value);';
    }

    /**
     * @return string
     */
    public function closureToMongo()
    {
        return '$return = (int) $value;';
    }
}
