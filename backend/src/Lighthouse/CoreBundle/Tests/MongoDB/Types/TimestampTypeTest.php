<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\MongoDB\Types\TimestampType;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use MongoTimestamp;

class TimestampTypeTest extends TestCase
{
    /**
     * @var TimestampType
     */
    protected $type;

    protected function setUp()
    {
        Type::registerType(TimestampType::NAME, TimestampType::getClassName());
        $this->type = Type::getType(TimestampType::NAME);
    }

    /**
     * @dataProvider convertToDatabaseValueProvider
     * @param $value
     * @param $expected
     */
    public function testConvertToDatabaseValue($value, $expected)
    {
        $mongoTimestamp = $this->type->convertToDatabaseValue($value);
        if (null === $expected) {
            $this->assertNull($mongoTimestamp);
        } else {
            $this->assertInstanceOf('MongoTimestamp', $mongoTimestamp);
            $this->assertEquals($expected, $mongoTimestamp->sec);
        }
    }

    /**
     * @return array
     */
    public function convertToDatabaseValueProvider()
    {
        return array(
            'DateTimestamp' => array(
                DateTimestamp::createFromTimestamp(1121),
                1121
            ),
            'MongoTimestamp' => array(
                new MongoTimestamp(1121, 2),
                1121
            ),
            'integer' => array(
                1121,
                1121
            ),
            'float' => array(
                1121.23,
                1121
            ),
            'string' => array(
                '2014-02-14T20:14:00+0400',
                null
            ),
            'null' => array(
                null,
                null
            ),
            'array' => array(
                array('sec' => 1211, 'inc' => 2),
                null
            )
        );
    }

    /**
     * @dataProvider convertToPHPValueProvider
     * @param $value
     * @param $expected
     */
    public function testConvertToPHPValue($value, $expected)
    {
        $this->assertEquals($expected, $this->type->convertToPHPValue($value));
    }

    /**
     * @return array
     */
    public function convertToPHPValueProvider()
    {
        return array(
            'MongoTimestamp' => array(
                new MongoTimestamp(1121),
                DateTimestamp::createFromTimestamp(1121)
            ),
            'null' => array(
                null,
                null
            ),
            'integer' => array(
                1121,
                null
            ),
            'float' => array(
                1121.23,
                null
            ),
            'string' => array(
                '2014-02-14T20:14:00+0400',
                null
            ),
            'array' => array(
                array('sec' => 1211, 'inc' => 2),
                null
            )
        );
    }
}
