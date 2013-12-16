<?php

namespace Lighthouse\CoreBundle\Tests\Types\Date;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

class DatePeriodTest extends TestCase
{
    public function testStartAndEndDateNotEqual()
    {
        $datePeriod = new DatePeriod("-30 days", new DateTimestamp("-3 days"));
        $this->assertNotEquals($datePeriod->getStartDate()->getTimestamp(), $datePeriod->getEndDate()->getTimestamp());
    }
}
