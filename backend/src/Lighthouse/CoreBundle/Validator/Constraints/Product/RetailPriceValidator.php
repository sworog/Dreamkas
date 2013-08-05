<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
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
        $retailPriceConstraints = array(
            new Money()
        );

        $retailMarkupConstrains = array(
            new Precision(),
            new Range(
                array(
                    'gte' => 0,
                    'gtMessage' => 'lighthouse.validation.errors.product.retailMarkup.range'
                )
            )
        );

        $comparePriceConstraints = array(
            new NumbersCompare(array('minField' => 'retailPriceMin', 'maxField' => 'retailPriceMax')),
        );

        $compareMarkupConstraints = array(
            new NumbersCompare(array('minField' => 'retailMarkupMin', 'maxField' => 'retailMarkupMax')),
        );

        $value->updateRetails();

        switch ($value->retailPricePreference) {
            case $value::RETAIL_PRICE_PREFERENCE_PRICE:
                $retailPriceMinValid = $this->validateValue(
                    $value->retailPriceMin,
                    $retailPriceConstraints,
                    'retailPriceMin'
                );
                if ($retailPriceMinValid) {
                    $this->context->validateValue(
                        $value->retailMarkupMin,
                        $retailMarkupConstrains,
                        'retailMarkupMin'
                    );
                }
                $retailPriceMaxValid = $this->validateValue(
                    $value->retailPriceMax,
                    $retailPriceConstraints,
                    'retailPriceMax'
                );
                if ($retailPriceMaxValid) {
                    $this->context->validateValue(
                        $value->retailMarkupMax,
                        $retailMarkupConstrains,
                        'retailMarkupMax'
                    );
                }
                if ($retailPriceMinValid && $retailPriceMaxValid) {
                    $this->context->validateValue($value, $comparePriceConstraints);
                }
                break;
            case $value::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $retailMarkupMinValid = $this->validateValue(
                    $value->retailMarkupMin,
                    $retailMarkupConstrains,
                    'retailMarkupMin'
                );
                if ($retailMarkupMinValid) {
                    $this->context->validateValue(
                        $value->retailPriceMin,
                        $retailPriceConstraints,
                        'retailPriceMin'
                    );
                }
                $retailMarkupMaxValid = $this->validateValue(
                    $value->retailMarkupMax,
                    $retailMarkupConstrains,
                    'retailMarkupMax'
                );
                if ($retailMarkupMaxValid) {
                    $this->context->validateValue(
                        $value->retailPriceMax,
                        $retailPriceConstraints,
                        'retailPriceMax'
                    );
                }
                if ($retailMarkupMinValid && $retailMarkupMaxValid) {
                    $this->context->validateValue($value, $compareMarkupConstraints);
                }
                break;
        }
    }

    /**
     * @param $value
     * @param Constraint|Constraint[] $constraints
     * @param string $subPath
     * @param null|string|string[] $groups
     * @return bool
     */
    protected function validateValue($value, $constraints, $subPath = '', $groups = null)
    {
        $countViolations = count($this->context->getViolations());
        $this->context->validateValue($value, $constraints, $subPath, $groups);
        if (count($this->context->getViolations()) == $countViolations) {
            return true;
        } else {
            return false;
        }
    }
}
