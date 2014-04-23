<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\PrecisionValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class PrecisionValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var PrecisionValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $this->validator = new PrecisionValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    public function testAnnotation()
    {
        $constraint = new Precision();

        $defaultOption = $constraint->getDefaultOption();
        $this->assertEquals('precision', $defaultOption);
    }

    /**
     * @dataProvider validProvider
     * @param $value
     * @throws \PHPUnit_Framework_Exception
     */
    public function testValid($value)
    {
        $this->context
                ->expects($this->never())
                ->method('addViolation');
        $constraint = new Precision();
        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return array(
            '76.93' => array('76.93'),
            '1' => array('1'),
            '100.100' => array('100.100'),
            'null' => array(null),
        );
    }

    /**
     * @dataProvider invalidProvider
     * @param $value
     * @throws \PHPUnit_Framework_Exception
     */
    public function testInvalid($value)
    {
        $this->context
            ->expects($this->once())
            ->method('addViolation');
        $constraint = new Precision();
        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function invalidProvider()
    {
        return array(
            '76.931' => array('76.931'),
            '1.001' => array('1.001'),
            '0.0010' => array('0.0010'),
        );
    }
}
