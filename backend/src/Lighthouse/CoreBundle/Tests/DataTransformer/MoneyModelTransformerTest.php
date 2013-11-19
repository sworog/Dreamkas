<?php

namespace Lighthouse\CoreBundle\Tests\DataTransformer;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class MoneyModelTransformerTest extends TestCase
{
    /**
     * @dataProvider invalidTransformValueProvider
     * @param mixed $value
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformInvalidValue($value)
    {
        $transformer = new MoneyModelTransformer(2);
        $transformer->transform($value);
    }

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
     * @param int $precision
     */
    public function testTransform($value, $expected, $precision)
    {
        $transformer = new MoneyModelTransformer($precision);
        $actual = $transformer->transform($value);
        $this->assertEquals($expected, $actual);
    }

    public function validTransformValueProvider()
    {
        return array(
            array(null, null, null),
            array(null, null, 2),
            array(new Money(1000), 10, 2),
            array(new Money(1112), 11.12, 2),
            array(new Money(1112), 111.2, 1),
            array(new Money(1112), 1.112, 3),
            array(new Money(null), null, null),
            array(new Money(null), null, 1),
            array(new Money(null), null, 2),
            array(new Money(null), null, 3),
            array(new Money(0), 0, 1),
            array(new Money(0), 0, 2),
            array(new Money(0), 0, 3),
            array(new Money(''), null, 1),
            array(new Money(''), null, 2),
            array(new Money(''), null, 3),
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
        $transformer = new MoneyModelTransformer($precision);
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
            array(11.12, 11.12, 0),
            array(11.12, 111.2, 1),
            array(11.12, 1112,  2),
            array(11.12, 11120,  3),
            array('11.12', 11.12, 0),
            array('11.12', 111.2, 1),
            array('11.12', 1112,  2),
            array('11.12', 11120,  3),
            array(10.89, 1089, 2),
        );
    }
}
