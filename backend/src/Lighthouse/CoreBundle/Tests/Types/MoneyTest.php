<?php

namespace Lighthouse\CoreBundle\Tests\Types;

use Lighthouse\CoreBundle\Types\Money;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validValueProvider
     * @param $value
     * @param $expected
     * @param bool $round
     */
    public function testSetCount($value, $expected, $round = false)
    {
        $money = new Money();
        $money->setCount($value, $round);

        $this->assertEquals($expected, $money->getCount());
    }

    public function validValueProvider()
    {
        return array(
            array(10.89, 10.89, false),
            array(10.12, 10.12, false),
            array(10.89, 11, true),
            array(10.12, 10, true),
            array(new Money(10.89), 10.89, false),
            array(new Money(10.12), 10.12, false),
            array(new Money(10.89), 11, true),
            array(new Money(10.12), 10, true),
        );
    }
}
