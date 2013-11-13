<?php

namespace Lighthouse\CoreBundle\Tests\Types;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\DatePeriod;

class DatePeriodTest extends TestCase
{
    public function testStartAndEndDateNotEqual()
    {
        $datePeriod = new DatePeriod("-30 days", "-3 days");
        $this->assertNotEquals($datePeriod->getStartDate()->getTimestamp(), $datePeriod->getEndDate()->getTimestamp());
    }
}
