<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\CompareObjectFixture;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompareValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ExecutionContextInterface;

class NumbersCompareValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var \Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompareValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $this->validator = new NumbersCompareValidator();
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
    public function testValidValues($minValue = null, $maxValue = null)
    {
        $this
            ->context
            ->expects($this->never())
            ->method('addViolationAt');

        $this->doValidate($minValue, $maxValue);
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
     */
    public function testInvalidValues($minValue, $maxValue)
    {
        $this
            ->context
            ->expects($this->once())
            ->method('addViolationAt');

        $this->doValidate($minValue, $maxValue);
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
     * @expectedExceptionMessage Expected argument of type Lighthouse\CoreBundle\Document\AbstractDocument
     * @dataProvider unexpectedValueTypeProvider
     */
    public function testValueUnexpectedType($value)
    {
        $options = array(
            'minField' => 'fieldMin',
            'maxField' => 'fieldMax',
            'message' => 'message'
        );
        $constraint = new NumbersCompare($options);

        $this->validator->validate($value, $constraint);
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
     * @expectedExceptionMessage Expected argument of type numeric
     * @dataProvider fieldUnexpectedTypesProvider
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

        $this->validator->validate($object, $constraint);
    }
}
