<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Precision;

class PrecisionValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotation()
    {
        $constraint = new Precision();

        $defaultOption = $constraint->getDefaultOption();
        $this->assertEquals('decimals', $defaultOption);
    }
}
