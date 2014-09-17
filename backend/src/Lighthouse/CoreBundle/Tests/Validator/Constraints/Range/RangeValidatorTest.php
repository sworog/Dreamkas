<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;
use stdClass;

class RangeValidatorTest extends ConstraintTestCase
{
    /**
     * @dataProvider validValuesProvider
     * @param $options
     * @param $value
     */
    public function testValidValues(array $options, $value = null)
    {
        $constraint = new Range($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    /**
     * @return array
     */
    public function validValuesProvider()
    {
        return array(
            'null gt 0' => array(
                array('gt' => 0),
                null,
            ),
            '1 gt 0' => array(
                array('gt' => 0),
                1,
            ),
            '1 gte 0' => array(
                array('gte' => 0),
                1,
            ),
            '1 gte 1' => array(
                array('gte' => 1),
                1,
            ),
            '0 lt 1' => array(
                array('lt' => 1),
                0,
            ),
            '-1 lt 1' => array(
                array('lt' => 1),
                -1,
            ),
            '1 lte 1' => array(
                array('lte' => 1),
                1,
            ),
            '0 lte 1' => array(
                array('lte' => 1),
                0,
            ),
            '0 lte 1, integer true' => array(
                array('lte' => 1, 'integer' => true),
                0,
            ),
            '4 gt 0, lt 5' => array(
                array('gt' => 0, 'lt' => 5),
                4
            ),
            '0 gte 0, lte 5' => array(
                array('gte' => 0, 'lte' => 5),
                0
            ),
            '5 gte 0, lte 5' => array(
                array('gte' => 0, 'lte' => 5),
                5
            ),
            '4 gte 0, lte 5' => array(
                array('gte' => 0, 'lte' => 5),
                4
            ),
            'int 4 gte 0, lte 5' => array(
                array('gte' => '0', 'lte' => '5'),
                4
            ),
            'string 4 gte 0, lte 5' => array(
                array('gte' => '0', 'lte' => '5'),
                '4'
            ),
            'float 4.1 gte 0.4, lte 5.23' => array(
                array('gte' => 0.4, 'lte' => 5.23),
                4.1
            ),
            'string 4.1 gte 0.4, lte 5.23' => array(
                array('gte' => '0.4', 'lte' => '5.23'),
                '4.1'
            ),
        );
    }

    /**
     * @dataProvider invalidValuesProvider
     * @param array $options
     * @param int|float $value
     * @param int|float $limit
     * @param string $expectedMessage
     */
    public function testValidationFail($options, $value, $limit, $expectedMessage)
    {
        $constraint = new Range($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertEquals($expectedMessage, $violation->getMessageTemplate());
        $this->assertConstraintViolationParameterEquals($value, '{{ value }}', $violation);
        $this->assertConstraintViolationParameterEquals($limit, '{{ limit }}', $violation);
    }

    /**
     * @return array
     */
    public function invalidValuesProvider()
    {
        return array(
            '1 gt 2' => array(
                array('gt' => 2),
                1,
                2,
                'lighthouse.validation.errors.range.gt'
            ),
            '0 gte 1' => array(
                array('gte' => 1),
                0,
                1,
                'lighthouse.validation.errors.range.gte'
            ),
            '0.9 gte 1' => array(
                array('gte' => 1),
                0.9,
                1,
                'lighthouse.validation.errors.range.gte'
            ),
            '2 lt 1'=> array(
                array('lt' => 1),
                2,
                1,
                'lighthouse.validation.errors.range.lt'
            ),
            '10 lt 1' => array(
                array('lt' => 1),
                10,
                1,
                'lighthouse.validation.errors.range.lt'
            ),
            '2 lte 1' => array(
                array('lte' => 1),
                2,
                1,
                'lighthouse.validation.errors.range.lte'
            ),
            '-1 gt 0, lt 5' => array(
                array('gt' => 0, 'lt' => 5),
                -1,
                0,
                'lighthouse.validation.errors.range.gt'
            )
        );
    }

    /**
     * @dataProvider notNumericValueProvider
     * @param array $options
     * @param $value
     */
    public function testNotNumericValue(array $options, $value)
    {
        $constraint = new Range($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertEquals('lighthouse.validation.errors.range.invalid', $violation->getMessageTemplate());
    }

    /**
     * @return array
     */
    public function notNumericValueProvider()
    {
        return array(
            'aaa' => array(
                array('gt' => 0),
                'aaa'
            ),
            'aaa, integer' => array(
                array('integer' => true, 'gt' => 0),
                'aaa'
            ),
            '1.2 float, integer' => array(
                array('integer' => true, 'gt' => 0),
                '1.2'
            ),
            '1.2 integer, integer' => array(
                array('integer' => true, 'gt' => 0),
                1.2
            ),
        );
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\MissingOptionsException
     * @expectedExceptionMessage Either option
     */
    public function testConstraintRejectEmptyConstructor()
    {
        new Range();
    }

    /**
     *
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @dataProvider notNumericLimitProvider
     * @param $options
     */
    public function testNotNumericLimit($options)
    {
        $constraint = new Range($options);

        $this->getValidator()->validate(1, $constraint, null);
    }

    /**
     * @return array
     */
    public function notNumericLimitProvider()
    {
        return array(
            'gt' => array(
                array('gt' => 'string')
            ),
            'lt' => array(
                array('lt' => 'string')
            ),
            'gte' => array(
                array('gte' => 'string')
            ),
            'lte' => array(
                array('lte' => 'string')
            ),
            '10,1' => array(
                array('lte' => '10,1')
            ),
            'true' => array(
                array('lte' => true)
            ),
            'false' => array(
                array('lte' => false)
            ),
            'empty string' => array(
                array('lte' => '')
            ),
            'stdClass' => array(
                array('lte' => new stdClass())
            ),
            'array' => array(
                array('lte' => array())
            ),
        );
    }
}
