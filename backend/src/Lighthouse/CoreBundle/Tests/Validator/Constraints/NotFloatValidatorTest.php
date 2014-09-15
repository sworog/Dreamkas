<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\NotFloat;

class NotFloatValidatorTest extends ConstraintTestCase
{
    /**
     * @dataProvider validationFailedProvider
     * @param $value
     */
    public function testValidationFailed($value)
    {
        $constraint = new NotFloat();

        $violations = $this->getValidator()->validate($value, $constraint, null);

        $this->assertCount(1, $violations);
        $this->assertTrue($violations->has(0));
        $violation = $violations->get(0);
        $this->assertEquals('lighthouse.validation.errors.not_float.invalid', $violation->getMessageTemplate());

        $parameters = $violation->getMessageParameters();
        $this->assertArrayHasKey('{{ value }}', $parameters);
        $this->assertEquals($value, $parameters['{{ value }}']);
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

        $violations = $this->getValidator()->validate($value, $constraint);
        $this->assertCount(0, $violations);
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
