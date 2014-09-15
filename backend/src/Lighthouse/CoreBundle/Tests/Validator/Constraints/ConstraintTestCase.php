<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\LegacyValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConstraintTestCase extends ContainerAwareTestCase
{
    /**
     * @return ValidatorInterface|LegacyValidator
     */
    protected function getValidator()
    {
        return $this->getContainer()->get('validator');
    }

    /**
     * @param string $expectedValue
     * @param string $parameterName
     * @param ConstraintViolationInterface $violation
     */
    public function assertConstraintViolationParameterEquals(
        $expectedValue,
        $parameterName,
        ConstraintViolationInterface $violation
    ) {
        $parameters = $violation->getMessageParameters();
        $this->assertArrayHasKey($parameterName, $parameters);
        $this->assertEquals($expectedValue, $parameters[$parameterName]);
    }
}
