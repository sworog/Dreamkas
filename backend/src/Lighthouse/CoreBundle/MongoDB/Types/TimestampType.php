<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\TimestampType as BaseTimestampType;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use MongoTimestamp;

class TimestampType extends BaseTimestampType
{
    /**
     * @param mixed $value
     * @return MongoTimestamp|null|void
     */
    public function convertToDatabaseValue($value)
    {
        if ($value instanceof DateTimestamp) {
            $value = $value->getMongoTimestamp();
        }
        return parent::convertToDatabaseValue($value);
    }

    /**
     * @param MongoTimestamp $value
     * @return DateTimestamp|null|string
     */
    public function convertToPHPValue($value)
    {
        if ($value instanceof MongoTimestamp) {
            return DateTimestamp::createFromMongoTimestamp($value);
        } else {
            return parent::convertToPHPValue($value);
        }
    }

    /**
     * @return string
     */
    public function closureToMongo()
    {
        return 'if ($value instanceof \\Lighthouse\\CoreBundle\\Types\\Date\\DateTimestamp) {
            $return = $value->getMongoTimestamp();
        } elseif ($value instanceof \\MongoTimestamp) {
            $return = $value;
        } elseif (null !== $value) {
            $return = new \\MongoTimestamp($value);
        } else {
            $return = null;
        }';
    }

    /**
     * @return string
     */
    public function closureToPHP()
    {
        return 'if ($value instanceof \\MongoTimestamp) {
            $return = \\Lighthouse\\CoreBundle\\Types\\Date\\DateTimestamp::createFromMongoTimestamp($value);
        } elseif (null !== $value) {
            $return = (string) $value;
        } else {
            $return = null;
        }';
    }
}
