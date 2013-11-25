<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

class QuantityType extends Type
{
    /**
     * @param Quantity $value
     * @return array|mixed
     */
    public function convertToDatabaseValue($value)
    {
        return array('count' => $value->getCount(), 'precision' => $value->getPrecision());
    }

    /**
     * @param null|\stdClass $value
     * @return Decimal|null
     */
    public function convertToPHPValue($value)
    {
        return null !== $value ? new Quantity($value->count, $value->precision) : null;
    }

    /**
     * @return string
     */
    public function closureToMongo()
    {
        return '$return = array("count" => $value->getCount(), "precision" => $value->getPrecision())';
    }

    /**
     * @return string
     */
    public function closureToPHP()
    {
        return <<<EOS
if (null !== \$value) {
    \$return = new \\Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity(\$value['count'], \$value['precision']);
} else {
    \$return = null;
}
EOS;

    }
}
