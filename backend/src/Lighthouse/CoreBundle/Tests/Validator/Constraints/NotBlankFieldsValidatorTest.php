<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields;

class NotBlankFieldsValidatorTest extends TestCase
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
