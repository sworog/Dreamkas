<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\InvoiceProduct;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraint;

class VatValidator extends ConstraintValidator
{
    /**
     * @param InvoiceProduct $value
     * @param Constraint|Vat $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $constraints = array(
            new NotBlank(),
            new Money(array('notBlank' => true)),
        );

        if ($value->invoice->includesVAT) {
            $this->validateValue($value->price, $constraints, 'price');
        } else {
            $this->validateValue($value->priceWithoutVAT, $constraints, 'priceWithoutVAT');
        }
    }
}
