<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Precision;

class PrecisionValidatorTest extends ConstraintTestCase
{
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
        $constraint = new Precision();
        $violations = $this->getValidator()->validate($value, $constraint, null);

        $this->assertCount(0, $violations);
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
        $constraint = new Precision();
        $violations = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violations);
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
