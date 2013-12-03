<?php

namespace Lighthouse\CoreBundle\Types\Date;

class DateInterval extends \DateInterval
{
    const INTERVAL_SPEC = 'P%yY%mM%dDT%hH%iM%sS';

    /**
     * @param string $intervalSpec
     */
    public function __construct($intervalSpec = 'P0Y')
    {
        parent::__construct($intervalSpec);
    }

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
        $parentInterval = parent::createFromDateString($time);
        return static::createFromDateInterval($parentInterval);
    }

    /**
     * @param \DateInterval $parentInterval
     * @return DateInterval
     */
    public static function createFromDateInterval(\DateInterval $parentInterval)
    {
        $dateInterval = new static();
        $dateInterval->y = $parentInterval->y;
        $dateInterval->m = $parentInterval->m;
        $dateInterval->d = $parentInterval->d;
        $dateInterval->h = $parentInterval->h;
        $dateInterval->i = $parentInterval->i;
        $dateInterval->s = $parentInterval->s;
        $dateInterval->days = $parentInterval->days;
        $dateInterval->invert = $parentInterval->invert;
        return $dateInterval;
    }

    /**
     * @return string
     */
    public function getIntervalSpec()
    {
        return strtr($this->format(self::INTERVAL_SPEC), array('-' => ''));
    }
}
