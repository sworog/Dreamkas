<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\NotFloat;
use Lighthouse\CoreBundle\Validator\Constraints\NotFloatValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class NotFloatValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var NotFloatValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $this->validator = new NotFloatValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    /**
     * @dataProvider validationFailedProvider
     * @param $value
     */
    public function testValidationFailed($value)
    {
        $constraint = new NotFloat();
        $this->context
             ->expects($this->once())
             ->method('addViolation')
             ->with(
                $this->equalTo('lighthouse.validation.errors.not_float.invalid'),
                $this->equalTo(array('{{ value }}' => $value))
            );

        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validationFailedProvider()
    {
        return array(
            array(10.1),
            array(10.01),
            array(0.01),
        );
    }

    /**
     * @dataProvider validationPassedProvider
     * @param $value
     */
    public function testValidationPassed($value)
    {
        $constraint = new NotFloat();
        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($value, $constraint);
    }

    public function validationPassedProvider()
    {
        return array(
            '10' => array(10),
            '10.0' => array(10.0),
            '10.000' => array(10.000),
            '0.00' => array(0.00),
            'null' => array(null),
        );
    }
}
