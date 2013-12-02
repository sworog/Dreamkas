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

    public function testCreateFromDateString()
    {
        $this->markTestIncomplete();
        $interval = DateInterval::createFromDateString('-1 days');
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Date\\DateInterval', $interval);
        $this->assertFalse($interval->isEmpty());
    }
}
