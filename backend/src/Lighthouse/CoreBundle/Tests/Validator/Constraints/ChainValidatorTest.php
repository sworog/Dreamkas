<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Chain;
use Lighthouse\CoreBundle\Validator\Constraints\ChainValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\ExecutionContextInterface;

class ChainValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    protected $violations;

    /**
     * @var ChainValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock(
            'Symfony\Component\Validator\ExecutionContext',
            array(),
            array(),
            '',
            false
        );

        $this->validator = new ChainValidator();
        $this->validator->initialize($this->context);

        $this->violations = $this->getMock(
            'Symfony\Component\Validator\ConstraintViolationList',
            array(),
            array(),
            '',
            false
        );
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    public function testValidationPasses()
    {
        $value = 6;

        $notBlank = new NotBlank();
        $range = new Range(array('min' => 5));
        $options = array($notBlank, $range);
        $constraint = new Chain($options);

        $this
            ->violations
            ->expects($this->any())
            ->method('count')
            ->will($this->returnValue(0));
        $this
            ->context
            ->expects($this->at(0))
            ->method('getViolations')
            ->will($this->returnValue($this->violations));

        $this
            ->context
            ->expects($this->at(1))
            ->method('validateValue')
            ->with($value, $notBlank, "", null);

        $this
            ->context
            ->expects($this->at(2))
            ->method('getViolations')
            ->will($this->returnValue($this->violations));

        $this
            ->context
            ->expects($this->at(3))
            ->method('validateValue')
            ->with($value, $range, "", null);

        $this->validator->validate($value, $constraint);
    }

    public function testValidationBreaksOnFirstValidator()
    {
        $value = 6;

        $notBlank = new NotBlank();
        $range = new Range(array('min' => 5));
        $options = array($notBlank, $range);
        $constraint = new Chain($options);

        $this
            ->violations
            ->expects($this->at(0))
            ->method('count')
            ->will($this->returnValue(0));

        $this
            ->violations
            ->expects($this->at(1))
            ->method('count')
            ->will($this->returnValue(1));

        $this
            ->context
            ->expects($this->at(0))
            ->method('getViolations')
            ->will($this->returnValue($this->violations));

        $this
            ->context
            ->expects($this->once())
            ->method('validateValue')
            ->with($value, $notBlank, "", null);

        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider invalidOptionsProvider
     * @expectedException Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testRejectConstructorWithInvalidConstraints($options)
    {
        new Chain($options);
    }

    /**
     * @return array
     */
    public function invalidOptionsProvider()
    {
        return array(
            array(
                'NotValid'
            ),
            array(
                array(1, '1'),
            ),
            array(
                array(new \stdClass(), new \stdClass())
            ),
            array(
                'constraints' => 'NotValid'
            )
        );
    }

    /**
     * @dataProvider missingOptionsProvider
     * @expectedException Symfony\Component\Validator\Exception\MissingOptionsException
     */
    public function testRejectConstructorWithMissingOptions($options)
    {
        new Chain($options);
    }

    /**
     * @return array
     */
    public function missingOptionsProvider()
    {
        return array(
            array(
                null,
            ),
        );
    }
}
