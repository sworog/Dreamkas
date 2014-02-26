<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use MongoTimestamp;

class TimestampType extends BaseType
{
    const TIMESTAMP = 'timestamp';

    /**
     * @param mixed $value
     * @return MongoTimestamp|null|void
     */
    public static function convertToMongo($value)
    {
        if ($value instanceof DateTimestamp) {
            return $value->getMongoTimestamp();
        } else if ($value instanceof MongoTimestamp) {
            return $value;
        } else if (null !== $value) {
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
        } else if (null !== $value) {
            return (string) $value;
        } else {
            return null;
        }
    }
}
