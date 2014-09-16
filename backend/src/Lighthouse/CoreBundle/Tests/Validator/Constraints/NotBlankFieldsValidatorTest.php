<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields;

class NotBlankFieldsValidatorTest extends ConstraintTestCase
{
    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "array"
     */
    public function testOptionsFieldsIsNotArray()
    {
        new NotBlankFields('min');
    }
}
