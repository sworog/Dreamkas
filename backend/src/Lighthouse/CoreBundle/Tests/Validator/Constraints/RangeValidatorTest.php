<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Range;
use Lighthouse\CoreBundle\Validator\Constraints\RangeValidator;
use Symfony\Component\Validator\ExecutionContextInterface;
use stdClass;

class RangeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutionContextInterface
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

    /**
     * @return array
     */
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
            array(
                array('gte' => '0', 'lte' => '5'),
                4
            ),
            array(
                array('gte' => '0', 'lte' => '5'),
                '4'
            ),
            array(
                array('gte' => 0.4, 'lte' => 5.23),
                4.1
            ),
            array(
                array('gte' => '0.4', 'lte' => '5.23'),
                '4.1'
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
     * @expectedException \Symfony\Component\Validator\Exception\MissingOptionsException
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
                'lighthouse.validation.errors.range.invalid',
                array()
            );

        $this->validator->validate($value, $constraint);
    }

    /**
     *
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @dataProvider notNumericLimitProvider
     */
    public function testNotNumericLimit($options)
    {
        $constraint = new Range($options);

        $this->validator->validate(1, $constraint);
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
