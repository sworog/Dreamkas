<?php

namespace Lighthouse\CoreBundle\Tests\Types;

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
}
