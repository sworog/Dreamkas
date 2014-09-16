<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassNumericRange;
use Symfony\Component\Validator\Constraint;
use stdClass;

class ClassNumericRangeValidatorTest extends ConstraintTestCase
{
    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\NullValueException
     * @expectedExceptionMessage field is null
     */
    public function testNullField()
    {
        $value = new stdClass();
        $value->min = 10;
        $value->max = 11;

        $constraint = new ClassNumericRange(
            array(
                'field' => null,
                'lt' => 'min'
            )
        );
        $this->getValidator()->validate($value, $constraint, null);
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException
     * @expectedExceptionMessage Neither the property "mid" nor one of the methods
     */
    public function testInvalidObjectFieldValue()
    {
        $value = new stdClass();
        $value->min = 10;
        $value->max = 11;

        $constraint = new ClassNumericRange(
            array(
                'field' => 'mid',
                'lt' => 'min'
            )
        );
        $this->getValidator()->validate($value, $constraint, null);
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException
     * @expectedExceptionMessage Neither the property "mid" nor one of the methods
     */
    public function testInvalidObjectCompareFieldValue()
    {
        $value = new stdClass();
        $value->min = 10;
        $value->max = 11;

        $constraint = new ClassNumericRange(
            array(
                'field' => 'max',
                'lt' => 'mid'
            )
        );
        $this->getValidator()->validate($value, $constraint, null);
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "object or array"
     */
    public function testInvalidValueNotObject()
    {

        $constraint = new ClassNumericRange(
            array(
                'field' => 'max',
                'lt' => 'min'
            )
        );
        $this->getValidator()->validate(9, $constraint, null);
    }

    public function testObjectValueIsNotNumeric()
    {
        $value = new stdClass();
        $value->max = 'aaa';

        $constraint = new ClassNumericRange(
            array(
                'field' => 'max',
                'lt' => 10
            )
        );

        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertEquals('lighthouse.validation.errors.range.invalid', $violation->getMessageTemplate());
        $this->assertEquals('max', $violation->getPropertyPath());
    }

    /**
     * @dataProvider validateProvider
     * @param int|null $fieldValue
     * @param array $options
     * @param string $expectedMessage
     */
    public function testValidationFailed($fieldValue, array $options, $expectedMessage)
    {
        $value = new stdClass();
        $value->max = $fieldValue;
        $value->min = 10;

        $constraint = new ClassNumericRange(
            $options + array(
                'field' => 'max',
            )
        );

        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertEquals($expectedMessage, $violation->getMessageTemplate());
        $this->assertEquals('max', $violation->getPropertyPath());
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            'gt' => array(
                9,
                array('gt' => 'min'),
                'lighthouse.validation.errors.range.gt'
            ),
            'lt' => array(
                11,
                array('lt' => 'min'),
                'lighthouse.validation.errors.range.lt'
            ),
            'gte' => array(
                9,
                array('gte' => 'min'),
                'lighthouse.validation.errors.range.gte'
            ),
            'lte' => array(
                11,
                array('lte' => 'min'),
                'lighthouse.validation.errors.range.lte'
            )
        );
    }

    /**
     * @dataProvider validationPassedProvider
     * @param int $fieldValue
     * @param array $options
     */
    public function testValidationPassed($fieldValue, array $options)
    {
        $value = new stdClass();
        $value->max = $fieldValue;
        $value->min = 10;

        $constraint = new ClassNumericRange(
            $options + array(
                'field' => 'max',
            )
        );

        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violations);
    }

    /**
     * @return array
     */
    public function validationPassedProvider()
    {
        return array(
            'lt' => array(
                9,
                array('lt' => 'min')
            ),
            'gt' => array(
                11,
                array('gt' => 'min')
            ),
            'gte' => array(
                10,
                array('gte' => 'min')
            ),
            'lte' => array(
                10,
                array('lte' => 'min')
            ),
            'null' => array(
                null,
                array('lte' => 'min')
            ),
        );
    }

    public function testConstraintTargets()
    {
        $constraint = new ClassNumericRange(
            array('field' => 'min', 'lt' => 'max')
        );
        $this->assertSame(Constraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }
}
