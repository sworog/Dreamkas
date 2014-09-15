<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Chain;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validator\LegacyValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChainValidatorTest extends ConstraintTestCase
{
    public function testValidationPasses()
    {
        $value = 6;

        $notBlank = new NotBlank();
        $range = new Range(array('min' => 5));
        $options = array($notBlank, $range);
        $constraint = new Chain($options);

        $violationList = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(0, $violationList);
    }

    /**
     * @dataProvider validationBreaksOnFirstFailure
     * @param $value
     * @param $expectedMessageTemplate
     */
    public function testValidationBreaksOnFirstFailure($value, $expectedMessageTemplate)
    {
        $notBlank = new NotBlank();
        $range = new Range(array('min' => 5));
        $range2 = new Range(array('min' => 6, 'minMessage' => 'invalid range'));

        $constraint = new Chain(
            array(
                $notBlank,
                $range,
                $range2
            )
        );

        $violationList = $this->getValidator()->validate($value, $constraint, null);
        $this->assertCount(1, $violationList);
        $this->assertEquals($expectedMessageTemplate, $violationList->get(0)->getMessageTemplate());
    }

    /**
     * @return array
     */
    public function validationBreaksOnFirstFailure()
    {
        return array(
            'blank' => array(
                '',
                'This value should not be blank.',
            ),
            'range' => array(
                4,
                'This value should be {{ limit }} or more.'
            ),
            'range2' => array(
                5,
                'invalid range'
            ),
        );
    }

    /**
     * @dataProvider invalidOptionsProvider
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @param $options
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
     * @expectedException \Symfony\Component\Validator\Exception\MissingOptionsException
     * @param $options
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
