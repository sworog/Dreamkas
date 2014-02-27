<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use MongoTimestamp;

class TimestampType extends BaseType
{
    const NAME = 'timestamp';

    /**
     * @param mixed $value
     * @return MongoTimestamp|null|void
     */
    public static function convertToMongo($value)
    {
        if ($value instanceof DateTimestamp) {
            return $value->getMongoTimestamp();
        } elseif ($value instanceof MongoTimestamp) {
            return $value;
        } elseif (is_numeric($value)) {
            return new MongoTimestamp($value);
        } else {
            return null;
        }
    }

    /**
     * @param MongoTimestamp $value
     * @return DateTimestamp|null|string
     */
    public static function convertToPHP($value)
    {
        if ($value instanceof MongoTimestamp) {
            return DateTimestamp::createFromMongoTimestamp($value);
        } else {
            return null;
        }
    }
}
