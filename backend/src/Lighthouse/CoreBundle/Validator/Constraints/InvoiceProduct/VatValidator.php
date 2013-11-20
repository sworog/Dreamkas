<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\InvoiceProduct;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class VatValidator extends ConstraintValidator
{
    /**
     * @param InvoiceProduct $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

    }
}
