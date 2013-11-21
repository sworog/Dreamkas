<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;

class PrecisionValidatorTest extends TestCase
{
    public function testAnnotation()
    {
        $constraint = new Precision();

        $defaultOption = $constraint->getDefaultOption();
        $this->assertEquals('precision', $defaultOption);
    }
}
