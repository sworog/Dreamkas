<?php

namespace Lighthouse\CoreBundle\Types\Date;

use DateTime;
use MongoTimestamp;
use MongoDate;
use DateTimeZone;

class DateTimestamp extends DateTime
{
    const RFC3339_USEC = 'Y-m-d\TH:i:s.uP';

    /**
     * @var int
     */
    public $inc;

    /**
     * @param string $time
     * @param DateTimeZone $timezone
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        if ($time instanceof DateTime) {
            $timezone = ($timezone) ?: $time->getTimezone();
            $time = $time->format('c');
        }
        parent::__construct($time, $timezone);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTimestamp();
    }

    /**
     * @return int
     */
    public function getUsec()
    {
        return (int) $this->format('u');
    }

    /**
     * @param string|DateTime $time
     * @return bool
     */
    public function equals($time)
    {
        /* @var DateTimestamp $dateTime */
        $dateTime = new static($time);
        return $dateTime->getTimestamp() == $this->getTimestamp();
    }

    /**
     * @param DateTime $datetime
     * @return bool
     */
    public function equalsDate(DateTime $datetime)
    {
        return $this->format('d-m-Y') === $datetime->format('d-m-Y');
    }

    /**
     * @param int $hours
     * @return DateTimestamp
     */
    public function setHours($hours)
    {
        return $this->modify($hours . $this->format(":i:s"));
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return (int) $this->format("G");
    }

    /**
     * @param int $minutes
     * @return DateTimestamp
     */
    public function setMinutes($minutes)
    {
        return $this->modify($this->format("G:") . $minutes . $this->format(":s"));
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        return (int) $this->format("i");
    }

    /**
     * @param int $seconds
     * @return DateTimestamp
     */
    public function setSeconds($seconds)
    {
        return $this->modify($this->format("G:i:") . $seconds);
    }

    /**
     * @return int
     */
    public function getSeconds()
    {
        return (int) $this->format("s");
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
     * @return MongoDate
     */
    public function getMongoDate()
    {
        if (0 == $this->getUsec()) {
            return new MongoDate($this->getTimestamp());
        } else {
            return new MongoDate($this->getTimestamp(), $this->getUsec());
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
     * @param MongoDate $mongoDate
     * @return DateTimestamp
     */
    public static function createFromMongoDate(MongoDate $mongoDate)
    {
        return static::createFromTimestamp($mongoDate->sec, $mongoDate->usec);
    }

    /**
     * @param int $timestamp
     * @param int $usec
     * @param DateTimeZone $timezone
     * @return static|DateTimestamp
     */
    public static function createFromTimestamp($timestamp, $usec = null, DateTimeZone $timezone = null)
    {
        $timezone = $timezone ?: new DateTimeZone(date_default_timezone_get());

        if (null !== $usec) {
            $new = new self(static::createFromFormat('U.u', $timestamp . '.' . $usec));
        } else {
            $new = new self(static::createFromFormat('U', $timestamp));
        }
        $new->setTimezone($timezone);

        return $new;
    }
}
