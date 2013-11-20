<?php

namespace Lighthouse\InvoiceProduct;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\InvoiceProduct\Vat;
use Symfony\Component\Validator\Constraint;

class VatValidatorTest extends ContainerAwareTestCase
{
    public function testConstraintTargets()
    {
        $constraint = new Vat();
        $this->assertEquals(Constraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }
}
