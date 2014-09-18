<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\CompareObjectFixture;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class NumbersCompareValidatorTest extends ConstraintTestCase
{
    /**
     * @dataProvider validValuesProvider
     * @param int $minValue
     * @param int $maxValue
     */
    public function testValidValues($minValue = null, $maxValue = null)
    {
        $violations = $this->doValidate($minValue, $maxValue);
        $this->assertCount(0, $violations);
    }

    /**
     * @return array
     */
    public function validValuesProvider()
    {
        return array(
            array(
                10,
                11,
            ),
            array(
                10.12,
                10.13,
            ),
            array(
                10,
                10,
            ),
            array(
                null,
                10,
            ),
            array(
                10,
                null,
            ),
        );
    }

    /**
     * @dataProvider invalidValuesProvider
     * @param int|float $minValue
     * @param int|float $maxValue
     */
    public function testInvalidValues($minValue, $maxValue)
    {
        $violations = $this->doValidate($minValue, $maxValue);
        $this->assertCount(1, $violations);
    }

    /**
     * @return array
     */
    public function invalidValuesProvider()
    {
        return array(
            array(
                11,
                10,
            ),
            array(
                11.12,
                11.11,
            ),
        );
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Lighthouse\CoreBundle\Document\AbstractDocument"
     * @dataProvider unexpectedValueTypeProvider
     * @param mixed $value
     */
    public function testValueUnexpectedType($value)
    {
        $options = array(
            'minField' => 'fieldMin',
            'maxField' => 'fieldMax',
            'message' => 'message'
        );
        $constraint = new NumbersCompare($options);

        $this->getValidator()->validate($value, $constraint, null);
    }

    public function unexpectedValueTypeProvider()
    {
        return array(
            array(null),
            array(new \stdClass),
            array(array()),
            array(1),
            array(false),
            array("test")
        );
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "numeric"
     * @dataProvider fieldUnexpectedTypesProvider
     * @param $minValue
     * @param $maxValue
     */
    public function testFieldUnexpectedType($minValue, $maxValue)
    {
        $this->doValidate($minValue, $maxValue);
    }

    /**
     * @return array
     */
    public function fieldUnexpectedTypesProvider()
    {
        return array(
            array(
                'aaa',
                123,
            ),
            array(
                123,
                'aaa',
            ),
            array(
                new \stdClass,
                123,
            ),
            array(
                123,
                new \stdClass,
            ),
            array(
                123.2,
                array(),
            ),
            array(
                array(),
                12.67,
            ),
        );
    }

    public function testConstraint()
    {
        $options = array(
            'minField' => 'fieldMin',
            'maxField' => 'fieldMax',
            'message' => 'message'
        );

        $constraint = new NumbersCompare($options);
        $this->assertEquals(Constraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }

    /**
     * @param $minValue
     * @param $maxValue
     * @return ConstraintViolationListInterface
     */
    protected function doValidate($minValue, $maxValue)
    {
        $options = array(
            'minField' => 'fieldMin',
            'maxField' => 'fieldMax',
            'message' => 'message'
        );
        $constraint = new NumbersCompare($options);

        $object = new CompareObjectFixture;
        $object->fieldMin = $minValue;
        $object->fieldMax = $maxValue;

        return $this->getValidator()->validate($object, $constraint, null);
    }
}
