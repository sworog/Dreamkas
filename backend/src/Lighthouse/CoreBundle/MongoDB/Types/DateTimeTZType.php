<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use DateTime;
use MongoDate;

class DateTimeTZType extends BaseType
{
    const DATETIMETZ = 'datetime_tz';

    /**
     * @param DateTime|null $value
     * @return array|null
     */
    public static function convertToMongo($value)
    {
        if ($value instanceof DateTime) {
            $dbValue = array(
                'date'   => new MongoDate($value->getTimestamp()),
                'year'   => (int) $value->format('Y'),
                'month'  => (int) $value->format('n'),
                'day'    => (int) $value->format('j'),
                'hour'   => (int) $value->format('G'),
                'minute' => (int) $value->format('i'),
                'second' => (int) $value->format('s'),
                'tz'     => $value->getTimezone()->getName(),
                'offset' => $value->getOffset(),
                'iso'    => $value->format(DateTime::ISO8601)
            );
        } else {
            $dbValue = null;
        }
        return $dbValue;
    }

    /**
     * @param array|MongoDate[]|string[] $value
     * @return DateTime|null
     */
    public static function convertToPhp($value)
    {
        if (is_array($value) && isset($value['iso'])) {
            $date = new DateTime($value['iso']);
        } else {
            $date = null;
        }
        return $date;
    }
}
