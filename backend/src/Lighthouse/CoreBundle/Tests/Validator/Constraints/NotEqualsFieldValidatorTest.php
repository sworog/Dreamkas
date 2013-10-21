<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\NotEqualsField;

class NotEqualsFieldValidatorTest extends TestCase
{
    public function testAnnotation()
    {
        $constraint = new NotEqualsField('fieldName');

        $defaultOption = $constraint->getDefaultOption();
        $this->assertEquals('field', $defaultOption);

        $requiredOptions = $constraint->getRequiredOptions();
        $this->assertContains('field', $requiredOptions);
    }
}
