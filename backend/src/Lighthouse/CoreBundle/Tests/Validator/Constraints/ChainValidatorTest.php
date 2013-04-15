<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Chain;
use Lighthouse\CoreBundle\Validator\Constraints\ChainValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ExecutionContext;

class ChainValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutionContext
     */
    protected $context;

    /**
     * @var RangeValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new ChainValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
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
