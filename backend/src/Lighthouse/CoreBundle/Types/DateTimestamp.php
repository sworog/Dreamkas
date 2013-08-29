<?php

namespace Lighthouse\CoreBundle\Types;

use DateTime;
use MongoTimestamp;

class DateTimestamp extends DateTime
{
    /**
     * @var int
     */
    public $inc;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTimestamp();
    }

    /**
     * @return MongoTimestamp
     */
    public function getMongoTimestamp()
    {
        if (null === $this->inc) {
            return new MongoTimestamp($this->getTimestamp());
        } else {
            return new MongoTimestamp($this->getTimestamp(), $this->inc);
        }
    }

    /**
     * @param MongoTimestamp $mongoTimestamp
     * @return static|DateTimestamp
     */
    public static function createFromMongoTimestamp(MongoTimestamp $mongoTimestamp)
    {
        $dateTimestamp = static::createFromTimestamp($mongoTimestamp->sec);
        $dateTimestamp->inc = $mongoTimestamp->inc;

        return $dateTimestamp;
    }

    /**
     * @param int $timestamp
     * @return static|DateTimestamp
     */
    public static function createFromTimestamp($timestamp)
    {
        $dateTimestamp = static::createFromFormat('U', $timestamp);
        return $dateTimestamp;
    }
}
