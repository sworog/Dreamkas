<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Validator\Constraints\Blank;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Range\MoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompare;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
use Symfony\Component\Validator\Constraint;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;

class RetailPriceValidator extends ConstraintValidator
{
    /**
     * @param Product $value
     * @param Constraint|RetailPrice $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $validPurchasePrice = $this->validatePurchasePrice($value);

        $retailPriceConstraints = $this->getRetailPriceConstraints($value, $constraint, $validPurchasePrice);

        $retailMarkupConstraints = $this->getRetailMarkupConstraints($value, $constraint, $validPurchasePrice);

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
                        $retailMarkupConstraints,
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
                        $retailMarkupConstraints,
                        'retailMarkupMax'
                    );
                }
                if ($retailPriceMinValid && $retailPriceMaxValid) {
                    $this->validateComparePrice($value, $constraint);
                }
                break;
            case $value::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $retailMarkupMinValid = $this->validateValue(
                    $value->retailMarkupMin,
                    $retailMarkupConstraints,
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
                    $retailMarkupConstraints,
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
                    $this->validateCompareMarkup($value, $constraint);
                }
                break;
        }
    }

    /**
     * @param Product $value
     * @return bool
     */
    protected function validatePurchasePrice(Product $value)
    {
        $purchasePriceConstraints = array(
            new Money()
        );
        return $this->validateValue($value->purchasePrice, $purchasePriceConstraints, 'purchasePrice');
    }

    /**
     * @param Product $value
     * @param Constraint|RetailPrice $constraint
     * @param bool $isValidPurchasePrice
     * @return array|Constraint[]
     */
    protected function getRetailPriceConstraints(Product $value, Constraint $constraint, $isValidPurchasePrice)
    {
        if ($isValidPurchasePrice && $this->isNull($value->purchasePrice)) {
            $retailPriceConstraints = array(
                new Blank(
                    array(
                        'message' => $constraint->blankRetailPriceMessage,
                    )
                )
            );
        } else {
            $retailPriceConstraints = array(
                new Money(),
            );

            if (!$this->isNull($value->purchasePrice)) {
                $retailPriceConstraints[] = new MoneyRange(
                    array(
                        'gte' => $value->purchasePrice,
                        'gteMessage' => $constraint->retailPriceGtePurchasePriceMessage
                    )
                );
            }
        }

        return $retailPriceConstraints;
    }

    /**
     * @param Product $value
     * @param Constraint|RetailPrice $constraint
     * @param boolean $isValidPurchasePrice
     * @return array|Constraint[]
     */
    protected function getRetailMarkupConstraints(Product $value, Constraint $constraint, $isValidPurchasePrice)
    {
        if ($isValidPurchasePrice && $this->isNull($value->purchasePrice)) {
            $retailMarkupConstraints = array(
                new Blank(
                    array(
                        'message' => $constraint->blankRetailMarkupMessage,
                    )
                )
            );
        } else {
            $retailMarkupConstraints = array(
                new Precision(),
                new Range(
                    array(
                        'gte' => 0,
                        'gteMessage' => $constraint->retailMarkupGteZeroMessage
                    )
                )
            );
        }

        return $retailMarkupConstraints;
    }

    /**
     * @param Product $value
     * @param Constraint|RetailPrice $constraint
     */
    protected function validateComparePrice(Product $value, Constraint $constraint)
    {
        $comparePriceConstraints = array(
            new NotBlankFields(
                array(
                    'fields' => array('retailPriceMin', 'retailPriceMax'),
                    'message' => $constraint->notBlankRetailPriceMessage,
                )
            ),
            new MoneyCompare(
                array(
                    'minField' => 'retailPriceMin',
                    'maxField' => 'retailPriceMax',
                    'message' => $constraint->compareRetailPriceMessage
                )
            ),
        );

        $this->context->validateValue($value, $comparePriceConstraints);
    }

    /**
     * @param Product $value
     * @param Constraint|RetailPrice $constraint
     */
    protected function validateCompareMarkup(Product $value, Constraint $constraint)
    {
        $compareMarkupConstraints = array(
            new NotBlankFields(
                array(
                    'fields' => array('retailMarkupMin', 'retailMarkupMax'),
                    'message' => $constraint->notBlankRetailMarkupMessage
                )
            ),
            new NumbersCompare(
                array(
                    'minField' => 'retailMarkupMin',
                    'maxField' => 'retailMarkupMax',
                    'message' => $constraint->compareRetailMarkupMessage,
                )
            ),
        );
        $this->context->validateValue($value, $compareMarkupConstraints);
    }
}
