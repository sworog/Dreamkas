<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassMoneyRange;
use Symfony\Component\Validator\Constraint;
use stdClass;

class ClassMoneyRangeValidatorTest extends ConstraintTestCase
{
    public function testLimitFields()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->minPrice = new Money(509);
        $value->maxPrice = new Money(1234);

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
            'lt' => 'maxPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    public function testNestedLimitFields()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->parent = new stdClass;
        $value->parent->minPrice = new Money(509);
        $value->parent->maxPrice = new Money(1234);

        $options = array(
            'field' => 'price',
            'gt' => 'parent.minPrice',
            'lt' => 'parent.maxPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    public function testNullLimitField()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->minPrice = null;

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    public function testNullValueField()
    {
        $value = new stdClass;
        $value->price = null;
        $value->minPrice = new Money(1011);

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testLimitIsNotMoney()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->minPrice = 509;

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $this->getValidator()->validate($value, $constraint, null);
    }

    public function testValueIsNotMoney()
    {
        $value = new stdClass;
        $value->price = 1011;
        $value->minPrice = new Money(509);

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);

        $this->assertCount(1, $violations);
        $this->assertTrue($violations->has(0));
        $violation = $violations->get(0);

        $this->assertEquals('lighthouse.validation.errors.money_range.not_numeric', $violation->getMessageTemplate());
        $this->assertEquals('price', $violation->getPropertyPath());
    }

    public function testValueAndLimitFormatInViolationMessage()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->minPrice = new Money(1509);

        $options = array(
            'field' => 'price',
            'gt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertConstraintViolationParameterEquals('10.11', '{{ value }}', $violation);
        $this->assertConstraintViolationParameterEquals('15.09', '{{ limit }}', $violation);
    }

    public function testConstraintTarget()
    {
        $options = array(
            'field' => 'price',
            'lt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);

        $this->assertEquals(Constraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }

    public function testConstraintValidatedBy()
    {
        $options = array(
            'field' => 'price',
            'lt' => 'minPrice',
        );

        $constraint = new ClassMoneyRange($options);

        $this->assertNotContains('Validator', $constraint->validatedBy());
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\NullValueException
     * @expectedExceptionMessage field is null
     */
    public function testFieldNull()
    {
        $value = new stdClass;
        $value->price = new Money(1011);
        $value->minPrice = new Money(1509);

        $options = array(
            'field' => null,
            'lt' => 'minPrice',
        );
        $constraint = new ClassMoneyRange($options);
        $this->getValidator()->validate($value, $constraint, null);
    }
}
