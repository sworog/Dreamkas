<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Callback;
use Lighthouse\CoreBundle\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Blank;

class CallbackValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotation()
    {
        $options = array(
            'constraints' => array(
                'name' => new NotBlank(),
                'email' => new Email(),
            ),
            'method' => 'check'
        );

        $annotation = new Callback($options);

        $targets = $annotation->getTargets();
        $this->assertEquals(Constraint::CLASS_CONSTRAINT, $targets);

        $requiredOptions = $annotation->getRequiredOptions();
        $this->assertContains('method', $requiredOptions);
        $this->assertContains('constraints', $requiredOptions);
    }

    /**
     * @dataProvider invalidAnnotationOptionsProvider
     * @param $options
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testInvalidAnnotationOptions($options)
    {
        new Callback($options);
        $this->assertFalse(false, 'Should throw ValidatorException');
    }

    public function invalidAnnotationOptionsProvider()
    {
        return array(
            array(
                null
            ),
            array(
                array('constraints' => array(false), 'method' => 'check'),
            ),
            array(
                array('constraints' => array(array(false)), 'method' => 'check'),
            ),
            array(
                array('constraints' => array(array(false, new NotBlank())), 'method' => 'check'),
            ),
            array(
                array('constraints' => false, 'method' => 'check'),
            ),
            array(
                array('constraints' => array(new NotBlank))
            ),
            array(
                array('constraints' => array(new NotBlank), 'method1' => null)
            )
        );
    }

    /**
     * @dataProvider validAnnotationOptionsProvider
     * @param $options
     */
    public function testValidAnnotationOptions($options)
    {
        $callback = new Callback($options);
        $this->assertInstanceOf('Lighthouse\CoreBundle\Validator\Constraints\Callback', $callback);
    }

    public function validAnnotationOptionsProvider()
    {
        return array(
            array(
                array('constraints' => array(array()), 'method' => 'check'),
            ),
            array(
                array(
                    'constraints' => array(
                        'name' => array(new NotBlank(), new Email()),
                        'email' => array(new Email(), new Blank()),
                    ),
                    'method' => 'check'
                ),
            ),
        );
    }

    public function testValidator()
    {
        $contextMock = $this->getMock(
            'Symfony\Component\Validator\ExecutionContext',
            array(),
            array(),
            '',
            false
        );

        $contextMock
            ->expects($this->exactly(2))
            ->method('validateValue')
            ->will($this->returnValue(null));

        $objectMock = $this->getMock(
            'Lighthouse\CoreBundle\Tests\Validator\Constraints\CallbackObjectFixture',
            array(),
            array(),
            '',
            false
        );

        $objectMock
            ->expects($this->once())
            ->method('check')
            ->will($this->returnValue(null));

        $options = array(
            'constraints' => array(
                'name' => new NotBlank(),
                'email' => new Email(),
            ),
            'method' => 'check'
        );

        $constraint = new Callback($options);

        $validator = new CallbackValidator();
        $validator->initialize($contextMock);

        $validator->validate($objectMock, $constraint);
    }
}
