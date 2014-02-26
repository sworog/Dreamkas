<?php

namespace Lighthouse\CoreBundle\Types\Date;

class DatePeriod
{
    /**
     * @var DateTimestamp
     */
    protected $startDate;

    /**
     * @var DateTimestamp
     */
    protected $endDate;

    /**
     * @param DateTimestamp|string $startDate
     * @param DateTimestamp|string $endDate
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $this->convertDate($startDate);
        $this->endDate = $this->convertDate($endDate);
    }

    /**
     * @param DateTimestamp|string $date
     * @return DateTimestamp
     */
    protected function convertDate($date)
    {
        if ($date instanceof DateTimestamp) {
            return $date;
        } else {
            return new DateTimestamp($date);
        }
    }

    /**
     * @return DateTimestamp
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return DateTimestamp
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return \DateInterval
     */
    public function diff()
    {
        return $this->endDate->diff($this->startDate);
    }
}
