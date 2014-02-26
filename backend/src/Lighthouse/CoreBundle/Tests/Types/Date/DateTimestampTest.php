<?php

namespace Lighthouse\CoreBundle\Tests\Types\Date;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use MongoTimestamp;
use MongoDate;

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

    public function testMongoTimestamp()
    {
        $firstMongoTimestamp = new MongoTimestamp(1212, 2);

        $date = DateTimestamp::createFromMongoTimestamp($firstMongoTimestamp);
        $secondMongoTimestamp = $date->getMongoTimestamp();
        $this->assertNotSame($firstMongoTimestamp, $secondMongoTimestamp);
        $this->assertEquals($firstMongoTimestamp, $secondMongoTimestamp);

        $notMongoTimestampDate = DateTimestamp::createFromTimestamp(1212);
        $thirdMongoTimestamp = $notMongoTimestampDate->getMongoTimestamp();
        $this->assertEquals(1212, $thirdMongoTimestamp->sec);
    }

    public function testMongoDate()
    {
        $firstMongoDate = new MongoDate();
        $date = DateTimestamp::createFromMongoDate($firstMongoDate);
        $secondMongoDate = $date->getMongoDate();
        $this->assertNotSame($firstMongoDate, $secondMongoDate);
        $this->assertSame($firstMongoDate->sec, $secondMongoDate->sec);
        $this->assertSame($firstMongoDate->usec, $secondMongoDate->usec);
        $this->assertEquals($firstMongoDate, $secondMongoDate);

        $notMongoDateDate = DateTimestamp::createFromTimestamp(1212);
        $thirdMongoDate = $notMongoDateDate->getMongoDate();
        $this->assertEquals(0, $thirdMongoDate->usec);
    }

    public function testCopy()
    {
        $timestamp = new DateTimestamp();
        $timestampCopy = $timestamp->copy();
        $this->assertNotSame($timestampCopy, $timestamp);
        $this->assertEquals($timestampCopy, $timestamp);
    }

    /**
     * @dataProvider createFromTimestampUsecProvider
     * @param int $usec
     */
    public function testCreateFromTimestampUsec($usec)
    {
        $timestamp = DateTimestamp::createFromTimestamp(1212, $usec);
        $this->assertSame($usec, $timestamp->getUsec());
    }

    /**
     * @return array
     */
    public function createFromTimestampUsecProvider()
    {
        return array(
            1 => array(1),
            10 => array(10),
            100 => array(100),
            1000 => array(1000),
            10000 => array(10000),
            100000 => array(100000),
            11 => array(11),
            111 => array(111),
            1111 => array(1111),
            11111 => array(11111),
            111111 => array(111111),
        );
    }
}
