<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\MoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\Range;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompare;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
use Symfony\Component\Validator\Constraint;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;

class RetailPriceValidator extends ConstraintValidator
{
    /**
     * @param Product $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $retailPriceConstraints = array(
            new Money(),
        );

        if (!$this->isNull($value->purchasePrice)) {
            $retailPriceConstraints[] = new MoneyRange(
                array(
                    'gte' => $value->purchasePrice->getCount(),
                    'gteMessage' => 'lighthouse.validation.errors.product.retailPrice.purchasePrice'
                )
            );
        }

        $retailMarkupConstrains = array(
            new Precision(),
            new Range(
                array(
                    'gte' => 0,
                    'gteMessage' => 'lighthouse.validation.errors.product.retailMarkup.range'
                )
            )
        );

        $comparePriceConstraints = array(
            new NotBlankFields(
                array(
                    'fields' => array('retailPriceMin', 'retailPriceMax'),
                    'message' => 'lighthouse.validation.errors.product.retailPrice.not_blank'
                )
            ),
            new MoneyCompare(
                array(
                    'minField' => 'retailPriceMin',
                    'maxField' => 'retailPriceMax',
                    'message' => 'lighthouse.validation.errors.product.retailPrice.compare'
                )
            ),
        );

        $compareMarkupConstraints = array(
            new NotBlankFields(
                array(
                    'fields' => array('retailMarkupMin', 'retailMarkupMax'),
                    'message' => 'lighthouse.validation.errors.product.retailMarkup.not_blank'
                )
            ),
            new NumbersCompare(
                array(
                    'minField' => 'retailMarkupMin',
                    'maxField' => 'retailMarkupMax',
                    'message' => 'lighthouse.validation.errors.product.retailMarkup.compare'
                )
            ),
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
}
