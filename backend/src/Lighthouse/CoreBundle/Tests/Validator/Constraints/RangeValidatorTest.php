<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Range;
use Lighthouse\CoreBundle\Validator\Constraints\RangeValidator;
use Symfony\Component\Validator\ExecutionContext;

class RangeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutionContext
     */
    protected $context;

    /**
     * @var RangeValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new RangeValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    /**
     * @dataProvider validValuesProvider
     */
    public function testValidValues($options, $value)
    {
        $this
            ->context
            ->expects($this->never())
            ->method('addViolation');

        $constraint = new Range($options);
        $this->validator->validate($value, $constraint);
    }

    public function validValuesProvider()
    {
        return array(
            array(
                array('gt' => 0),
                null,
            ),
            array(
                array('gt' => 0),
                1,
            ),
            array(
                array('gte' => 0),
                1,
            ),
            array(
                array('gte' => 1),
                1,
            ),
            array(
                array('lt' => 1),
                0,
            ),
            array(
                array('lt' => 1),
                -1,
            ),
            array(
                array('lte' => 1),
                1,
            ),
            array(
                array('lte' => 1),
                0,
            ),
            array(
                array('gt' => 0, 'lt' => 5),
                4
            ),
            array(
                array('gte' => 0, 'lte' => 5),
                0
            ),
            array(
                array('gte' => 0, 'lte' => 5),
                5
            ),
            array(
                array('gte' => 0, 'lte' => 5),
                4
            ),
        );
    }

    /**
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValues($options, $value, $limit, $message)
    {
        $this
            ->context
            ->expects($this->once())
            ->method('addViolation')
            ->with(
                $message,
                array(
                    '{{ value }}' => $value,
                    '{{ limit }}' => $limit
                )
            );

        $constraint = new Range($options);
        $this->validator->validate($value, $constraint);
    }

    public function invalidValuesProvider()
    {
        return array(
            array(
                array('gt' => 2),
                1,
                2,
                'lighthouse.validation.errors.range.gt'
            ),
            array(
                array('gte' => 1),
                0,
                1,
                'lighthouse.validation.errors.range.gte'
            ),
            array(
                array('gte' => 1),
                0.9,
                1,
                'lighthouse.validation.errors.range.gte'
            ),
            array(
                array('lt' => 1),
                2,
                1,
                'lighthouse.validation.errors.range.lt'
            ),
            array(
                array('lt' => 1),
                10,
                1,
                'lighthouse.validation.errors.range.lt'
            ),
            array(
                array('lte' => 1),
                2,
                1,
                'lighthouse.validation.errors.range.lte'
            ),
            array(
                array('gt' => 0, 'lt' => 5),
                -1,
                0,
                'lighthouse.validation.errors.range.gt'
            )
        );
    }

    /**
     * @expectedException Symfony\Component\Validator\Exception\MissingOptionsException
     * @expectedExceptionMessage Either option
     */
    public function testConstraintRejectEmptyConstructor()
    {
        new Range();
    }

    public function testNotNumericValue()
    {
        $constraint = new Range(array('gt' => 10));

        $value = 'string';

        $this
            ->context
            ->expects($this->once())
            ->method('addViolation')
            ->with(
                'lighthouse.validation.errors.range.not_numeric',
                array(
                    '{{ value }}' => $value
                )
            );

        $this->validator->validate($value, $constraint);
    }
}
