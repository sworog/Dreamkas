<?php

namespace Lighthouse\CoreBundle\Tests\Types\Date;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Date\DateInterval;

class DateIntervalTest extends TestCase
{
    /**
     * @param string $intervalSpec
     * @param boolean $expectedResult
     * @dataProvider isEmptyProvider
     */
    public function testIsEmpty($intervalSpec, $expectedResult)
    {
        $interval = new DateInterval($intervalSpec);
        $this->assertSame($expectedResult, $interval->isEmpty());
    }

    /**
     * @return array
     */
    public function isEmptyProvider()
    {
        return array(
            array('P0D', true),
            array('P1D', false),
            array('P0M', true),
            array('P1M', false),
            array('P0Y', true),
            array('P1Y', false),
            array('PT0M', true),
        );
    }

    /**
     * @param string $time
     * @dataProvider createFromDateStringProvider
     */
    public function testCreateFromDateString($time)
    {
        $parentInterval = \DateInterval::createFromDateString($time);
        $interval = DateInterval::createFromDateString($time);
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Date\\DateInterval', $interval);
        $this->assertFalse($interval->isEmpty());

        $date1 = new \DateTime('2013-01-13 00:00:00');
        $date2 = clone $date1;
        $date1->add($parentInterval);
        $date2->add($interval);

        $this->assertSame($date1->format(\DateTime::ISO8601), $date2->format(\DateTime::ISO8601));
        $this->assertSame($date1->getTimestamp(), $date2->getTimestamp());
    }

    /**
     * @param $time
     * @param $expectedIntervalSpec
     * @dataProvider createFromDateStringProvider
     */
    public function testGetIntervalSpec($time, $expectedIntervalSpec)
    {
        $interval = DateInterval::createFromDateString($time);
        $this->assertSame($expectedIntervalSpec, $interval->getIntervalSpec());
    }

    /**
     * @return array
     */
    public function createFromDateStringProvider()
    {
        return array(
            '-1 days' => array('-1 days', 'P0Y0M1DT0H0M0S'),
            '1 days' => array('1 days', 'P0Y0M1DT0H0M0S'),
            '-3 seconds' => array('-3 seconds', 'P0Y0M0DT0H0M3S'),
            '5 second' => array('5 second', 'P0Y0M0DT0H0M5S'),
            '-1 week -2 hours' => array('-1 week -2 hours', 'P0Y0M7DT2H0M0S')
        );
    }
}
