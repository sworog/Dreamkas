<?php

namespace Lighthouse\CoreBundle\Tests\Validator;

use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExceptionalValidatorTest extends ContainerAwareTestCase
{
    /**
     * @return ExceptionalValidator
     */
    protected function getValidator()
    {
        return $this->getContainer()->get('lighthouse.core.validator');
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     */
    public function testValidateValueFailed()
    {
        $constraint = new NotBlank();
        $value = null;
        $this->getValidator()->validateValue($value, $constraint);
    }

    public function testValidateValue()
    {
        $constraint = new NotBlank();
        $value = 1;
        $this->assertCount(0, $this->getValidator()->validateValue($value, $constraint));
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     */
    public function testValidatePropertyFailed()
    {
        $object = new ValidateTestObject();
        $this->getValidator()->validateProperty($object, 'field');
    }

    public function testValidateProperty()
    {
        $object = new ValidateTestObject();
        $object->setField('1');
        $this->assertCount(0, $this->getValidator()->validateProperty($object, 'field'));
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     */
    public function testValidateFailed()
    {
        $object = new ValidateTestObject();
        $this->getValidator()->validate($object);
    }

    public function testValidate()
    {
        $object = new ValidateTestObject();
        $object->setField('1');
        $this->assertCount(0, $this->getValidator()->validate($object));
    }

    public function testValidationFailedException()
    {
        try {
            $object = new ValidateTestObject();
            $this->getValidator()->validate($object);
            $this->assertFalse(true, 'ValidationFailedException was not thrown');
        } catch (ValidationFailedException $e) {
            $this->assertCount(1, $e->getConstraintViolationList());
            $violation = $e->getConstraintViolationList()->get(0);
            $this->assertEquals('This value should not be blank.', $violation->getMessageTemplate());
        }
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     */
    public function testValidatePropertyValueFailed()
    {
        $object = new ValidateTestObject();
        $object->setField('1');
        $this->getValidator()->validatePropertyValue($object, 'field', null);
    }

    public function testValidatePropertyValue()
    {
        $object = new ValidateTestObject();
        $object->setField('1');
        $this->assertCount(0, $this->getValidator()->validatePropertyValue($object, 'field', '2'));
    }
}
