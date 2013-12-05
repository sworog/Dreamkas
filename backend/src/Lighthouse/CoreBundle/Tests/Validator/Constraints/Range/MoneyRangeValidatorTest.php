<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Range;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\Constraints\Range\MoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\Range\MoneyRangeValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class MoneyRangeValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var MoneyRangeValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $numericFactory = new NumericFactory(2, 2);
        $moneyTransformer = new MoneyModelTransformer($numericFactory);
        $this->validator = new MoneyRangeValidator($moneyTransformer);
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    /**
     * @param array $options
     * @param $value
     *
     * @dataProvider validValuesProvider
     */
    public function testValidValues(array $options, $value)
    {
        $this
            ->context
            ->expects($this->never())
            ->method('addViolationAt');

        $this
            ->context
            ->expects($this->never())
            ->method('addViolation');

        $constraint = new MoneyRange($options);
        $this->validator->validate($value, $constraint);
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
     */
    public function testInvalidOptions($options)
    {
        $value = new Money(1011);

        $constraint = new MoneyRange($options);
        $this->validator->validate($value, $constraint);
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
                array('gt' => 'aaaa')
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
     */
    public function testInvalidValue($value)
    {
        $options = array(
            'gt' => new Money(10),
            'lt' => new Money(20),
            'invalidMessage' => 'invalidMessage',
        );

        $this
            ->context
            ->expects($this->never())
            ->method('addViolationAt');

        $this
            ->context
            ->expects($this->once())
            ->method('addViolation')
            ->with(
                'invalidMessage'
            );

        $constraint = new MoneyRange($options);
        $this->validator->validate($value, $constraint);
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
                'aaaa'
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

        $this
            ->context
            ->expects($this->never())
            ->method('addViolationAt');

        $this
            ->context
            ->expects($this->once())
            ->method('addViolation')
            ->with(
                'lighthouse.validation.errors.range.gt',
                array(
                    '{{ value }}' => '10.11',
                    '{{ limit }}' => '15.09'
                )
            );

        $constraint = new MoneyRange($options);
        $this->validator->validate($value, $constraint);
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
