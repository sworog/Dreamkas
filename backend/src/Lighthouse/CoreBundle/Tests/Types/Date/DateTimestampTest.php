<?php

namespace Lighthouse\CoreBundle\Tests\Types\Date;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

class DateTimestampTest extends TestCase
{
    public function testGetMongoDate()
    {
        $time = strtotime("-30 days");
        $timestamp = new DateTimestamp("-30 days");
        $this->assertEquals($time, $timestamp->getTimestamp());
        $mongoDate = $timestamp->getMongoDate();
        $this->assertInstanceOf('\\MongoDate', $mongoDate);
        $this->assertEquals($time, $mongoDate->sec);

        $timestamp2 = new DateTimestamp("-3 days");
        $this->assertNotEquals($timestamp->getMongoDate()->sec, $timestamp2->getMongoDate()->sec);
    }
    
    public function testSetHourSetMinuteSetSecond()
    {
        $dateTime = new DateTimestamp('16-12-2013 16:43:57');
        $expectedDateTimeChangeHour = new DateTimestamp('16-12-2013 12:43:57');
        $expectedDateTimeChangeMinute = new DateTimestamp('16-12-2013 16:25:57');
        $expectedDateTimeChangeSecond = new DateTimestamp('16-12-2013 16:43:23');
        
        $changeHourDateTime = clone $dateTime;
        $changeHourDateTime->setHours(12);
        $this->assertTrue($expectedDateTimeChangeHour->equals($changeHourDateTime));

        $changeMinuteDateTime = clone $dateTime;
        $changeMinuteDateTime->setMinutes(25);
        $this->assertTrue($expectedDateTimeChangeMinute->equals($changeMinuteDateTime));

        $changeSecondDateTime = clone $dateTime;
        $changeSecondDateTime->setSeconds(23);
        $this->assertTrue($expectedDateTimeChangeSecond->equals($changeSecondDateTime));
    }

    public function testGetHoursGetMinutesGetSeconds()
    {
        $dateTime = new DateTimestamp('16-12-2013 16:43:57');

        $this->assertEquals(16, $dateTime->getHours());
        $this->assertEquals(43, $dateTime->getMinutes());
        $this->assertEquals(57, $dateTime->getSeconds());
    }

    public function testToString()
    {
        $time = strtotime("-30 days");
        $timestamp = new DateTimestamp("-30 days");
        $this->assertEquals($time, $timestamp->getTimestamp());
        $this->assertEquals("$time", (string) $timestamp);
    }

    public function testEqualDate()
    {
        $dateOne = new DateTimestamp('16-12-2013 16:43:57');
        $dateTwo = new DateTimestamp('16-11-2013 15:34:54');
        $dateThree = new DateTimestamp('16-12-2013 14:34:32');

        $this->assertTrue($dateOne->equalsDate($dateThree));
        $this->assertFalse($dateOne->equalsDate($dateTwo));
    }
}
