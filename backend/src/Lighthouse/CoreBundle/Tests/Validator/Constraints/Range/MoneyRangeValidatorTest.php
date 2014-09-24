<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Range\MoneyRange;

class MoneyRangeValidatorTest extends ConstraintTestCase
{
    /**
     * @param array $options
     * @param $value
     *
     * @dataProvider validValuesProvider
     */
    public function testValidValues(array $options, $value)
    {
        $constraint = new MoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    /**
     * @return array
     */
    public function validValuesProvider()
    {
        return array(
            array(
                array('gt' => new Money(0)),
                null,
            ),
            array(
                array('gt' => new Money(0)),
                new Money(),
            ),
            array(
                array('gt' => new Money(0)),
                new Money(''),
            ),
            array(
                array('gt' => new Money(0)),
                new Money(1),
            ),
            array(
                array('gte' => new Money(0)),
                new Money(1),
            ),
            array(
                array('gte' => new Money(1)),
                new Money(1),
            ),
            array(
                array('lt' => new Money(1)),
                new Money(0),
            ),
            array(
                array('lt' => new Money(1)),
                new Money(-1),
            ),
            array(
                array('lte' => new Money(1)),
                new Money(1),
            ),
            array(
                array('lte' => new Money(1)),
                new Money(0),
            ),
            array(
                array('gt' => new Money(0), 'lt' => new Money(5)),
                new Money(4)
            ),
            array(
                array('gte' => new Money(0), 'lte' => new Money(5)),
                new Money(0)
            ),
            array(
                array('gte' => new Money(0), 'lte' => new Money(5)),
                new Money(5)
            ),
            array(
                array('gte' => new Money(0), 'lte' => new Money(5)),
                new Money(4)
            ),
            array(
                array('gte' => new Money(40), 'lte' => new Money(523)),
                new Money(41)
            ),
        );
    }

    /**
     * @dataProvider invalidOptionsProvider
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @param $options
     */
    public function testInvalidOptions($options)
    {
        $value = new Money(1011);

        $constraint = new MoneyRange($options);
        $this->getValidator()->validate($value, $constraint, null);
    }

    public function invalidOptionsProvider()
    {
        return array(
            array(
                array('gt' => '')
            ),
            array(
                array('gt' => 123)
            ),
            array(
                array('gt' => 123.23)
            ),
            array(
                array('gt' => '123')
            ),
            array(
                array('gt' => '123.23')
            ),
            array(
                array('gt' => 'string')
            ),
            array(
                array('gt' => new \stdClass())
            ),
            array(
                array('gt' => array())
            )
        );
    }

    /**
     * @dataProvider invalidValueProvider
     * @param $value
     */
    public function testInvalidValue($value)
    {
        $options = array(
            'gt' => new Money(10),
            'lt' => new Money(20),
            'invalidMessage' => 'invalidMessage',
        );

        $constraint = new MoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $this->assertEquals('invalidMessage', $violations->get(0)->getMessageTemplate());
    }

    public function invalidValueProvider()
    {
        return array(
            array(
                ''
            ),
            array(
                123
            ),
            array(
                123.23
            ),
            array(
                '123'
            ),
            array(
                '123.23'
            ),
            array(
                'string'
            ),
            array(
                new \stdClass()
            ),
            array(
                array()
            ),
        );
    }

    public function testValueAndLimitFormatInViolationMessage()
    {
        $value = new Money(1011);

        $options = array(
            'gt' => new Money(1509),
        );

        $constraint = new MoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertEquals('lighthouse.validation.errors.range.gt', $violation->getMessageTemplate());
        $this->assertConstraintViolationParameterEquals('10.11', '{{ value }}', $violation);
        $this->assertConstraintViolationParameterEquals('15.09', '{{ limit }}', $violation);
    }

    public function testConstraintValidatedBy()
    {
        $options = array(
            'lt' => new Money(1011),
        );

        $constraint = new MoneyRange($options);

        $this->assertNotContains('Validator', $constraint->validatedBy());
    }
}
