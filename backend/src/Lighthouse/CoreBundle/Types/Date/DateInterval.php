<?php

namespace Lighthouse\CoreBundle\Types\Date;

class DateInterval extends \DateInterval
{
    const INTERVAL_SPEC = 'P%yY%mM%dDT%hH%iM%sS';

    /**
     * @return bool
     */
    public function isEmpty()
    {
        if (0 == $this->y
            && 0 == $this->m
            && 0 == $this->d
            && 0 == $this->h
            && 0 == $this->m
            && 0 == $this->s
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $time
     * @return DateInterval
     */
    public static function createFromDateString($time)
    {
        $dateInterval = parent::createFromDateString($time);
        return static::createFromDateInterval($dateInterval);
    }

    /**
     * @param \DateInterval $dateInterval
     * @return DateInterval
     */
    public static function createFromDateInterval(\DateInterval $dateInterval)
    {
        return new static($dateInterval->format(self::INTERVAL_SPEC));
    }

    /**
     * @return string
     */
    public function getIntervalSpec()
    {
        return $this->format(self::INTERVAL_SPEC);
    }
}
