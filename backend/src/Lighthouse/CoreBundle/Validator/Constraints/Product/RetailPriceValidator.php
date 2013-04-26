<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RetailPriceValidator extends ConstraintValidator
{
    /*****
     * @param Product $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $retailPriceConstraint = new Money();
        $retailMarkupPrecision = new Precision();
        $retailMarkupRange = new Range(
            array(
                'gt' => -100,
                'gtMessage' => 'lighthouse.validation.errors.product.retailMarkup.range'
            )
        );

        $value->updateRetails();
        $countViolations = count($this->context->getViolations());

        switch ($value->retailPricePreference) {
            case $value::RETAIL_PRICE_PREFERENCE_PRICE:
                $this->context->validateValue($value->retailPrice, $retailPriceConstraint, 'retailPrice');
                if (count($this->context->getViolations()) == $countViolations) {
                    $this->context->validateValue($value->retailMarkup, $retailMarkupPrecision, 'retailMarkup');
                    $this->context->validateValue($value->retailMarkup, $retailMarkupRange, 'retailMarkup');
                }
                break;
            case $value::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $this->context->validateValue($value->retailMarkup, $retailMarkupPrecision, 'retailMarkup');
                $this->context->validateValue($value->retailMarkup, $retailMarkupRange, 'retailMarkup');
                if (count($this->context->getViolations()) == $countViolations) {
                    $this->context->validateValue($value->retailPrice, $retailPriceConstraint, 'retailPrice');
                }
                break;
        }
    }
}
