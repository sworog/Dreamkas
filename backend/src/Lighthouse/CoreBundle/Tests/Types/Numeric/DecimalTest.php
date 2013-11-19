<?php

namespace Lighthouse\CoreBundle\Tests\Types\Numeric;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;

class DecimalTest extends TestCase
{
    public function testCreateFromNumeric()
    {
        $decimal = Decimal::createFromNumeric("12.11", 2);

        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Decimal', $decimal);
        $this->assertEquals(2, $decimal->getPrecision());
        $this->assertEquals(1211, $decimal->getCount());
        $this->assertEquals('12.11', $decimal->toNumber());
        $this->assertEquals('12.11', (string) $decimal);
        $this->assertSame(12.11, $decimal->toNumber());
    }

    /**
     * @param string $numeric
     * @param int $precision
     * @param string $operand
     * @param int $roundMode
     * @param string $expectedResult
     * @param int $expectedCount
     * @dataProvider mulProvider
     */
    public function testMulRounding($numeric, $precision, $operand, $roundMode, $expectedResult, $expectedCount)
    {
        $decimal = Decimal::createFromNumeric($numeric, $precision);
        $result = $decimal->mul($operand, $roundMode);
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Decimal', $result);
        $this->assertEquals($expectedResult, $result->toString());
        $this->assertEquals($precision, $result->getPrecision());
        $this->assertEquals($expectedCount, $result->getCount());
    }

    /**
     * @return array
     */
    public function mulProvider()
    {
        return array(
            array('12.11', 2, '5.5', Decimal::ROUND_HALF_UP, '66.61', 6661),
            array('12.11', 2, '5.5', Decimal::ROUND_HALF_DOWN, '66.60', 6660),
            array('12.11', 2, '4.4', Decimal::ROUND_HALF_UP, '53.28', 5328),
        );
    }

    /**
     * @param string $numeric
     * @param int $precision
     * @param string $operand
     * @param int $roundMode
     * @param string $expectedResult
     * @param int $expectedCount
     * @dataProvider divProvider
     */
    public function testDivRounding($numeric, $precision, $operand, $roundMode, $expectedResult, $expectedCount)
    {
        $decimal = Decimal::createFromNumeric($numeric, $precision);
        $result = $decimal->div($operand, $roundMode);
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Decimal', $result);
        $this->assertEquals($expectedResult, $result->toString());
        $this->assertEquals($precision, $result->getPrecision());
        $this->assertEquals($expectedCount, $result->getCount());
    }


    /**
     * @return array
     */
    public function divProvider()
    {
        return array(
            array('12.11', 2, '5.29', Decimal::ROUND_HALF_UP, '2.29', 229),
            array('12.11', 2, '5.29', Decimal::ROUND_HALF_DOWN, '2.28', 228),
            array('-12.11', 2, '5.29', Decimal::ROUND_HALF_UP, '-2.29', -229),
            array('12.11', 2, '-5.29', Decimal::ROUND_HALF_UP, '-2.29', -229),
            array('-12.11', 2, '-5.29', Decimal::ROUND_HALF_UP, '2.29', 229),
            array('12.11', 2, '4.4', Decimal::ROUND_HALF_UP, '2.75', 275),
            array('12.11', 2, '4.4', Decimal::ROUND_HALF_DOWN, '2.75', 275),
        );
    }
}
