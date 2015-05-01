<?php

namespace Lighthouse\CoreBundle\Tests\DataTransformer;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class MoneyModelTransformerTest extends TestCase
{
    /**
     * @dataProvider invalidTransformValueProvider
     * @param mixed $value
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformInvalidValue($value)
    {
        $numericFactory = new NumericFactory(2, 2);
        $transformer = new MoneyModelTransformer($numericFactory);
        $transformer->transform($value);
    }

    /**
     * @return array
     */
    public function invalidTransformValueProvider()
    {
        return array(
            array(new \stdClass()),
            array(array()),
            array(1),
            array(2.5),
            array('string'),
            array(false),
            array(true)
        );
    }

    /**
     * @dataProvider validTransformValueProvider
     * @param mixed $value
     * @param int $expected
     */
    public function testTransform($value, $expected)
    {
        $numericFactory = new NumericFactory(2, 2);
        $transformer = new MoneyModelTransformer($numericFactory);
        $actual = $transformer->transform($value);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function validTransformValueProvider()
    {
        return array(
            array(null, null),
            array(new Money(1000), 10.00),
            array(new Money(1112), 11.12),
            array(new Money(null), null),
            array(new Money(0), 0),
            array(new Money(''), null),
        );
    }

    /**
     * @dataProvider validReverseTransformValueProvider
     * @param mixed $value
     * @param Money $expected
     * @param int $precision
     */
    public function testReverseTransform($value, $expected, $precision)
    {
        $numericFactory = new NumericFactory(2, $precision);
        $transformer = new MoneyModelTransformer($numericFactory);
        $actual = $transformer->reverseTransform($value);
        $this->assertEquals($expected, $actual->getCount());
    }

    /**
     * @return array
     */
    public function validReverseTransformValueProvider()
    {
        return array(
            array(null, null, null),
            array('', '', null),
            array(11.12, 11, 0),
            array(11.12, 111, 1),
            array(11.12, 1112,  2),
            array(11.12, 11120,  3),
            array('11.12', 11, 0),
            array('11.12', 111, 1),
            array('11.12', 1112,  2),
            array('11.12', 11120,  3),
            array(10.89, 1089, 2),
        );
    }
}
