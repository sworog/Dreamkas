<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\StoreProduct;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassMoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassNumericRange;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;
use Symfony\Component\Validator\Constraint;

class RetailPriceValidator extends ConstraintValidator
{
    /**
     * @param StoreProduct $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $retailPriceConstraints = array(
            new Money(),
        );
        $retailPriceClassConstraints = array(
            new ClassMoneyRange(array(
                'field' => 'retailPrice',
                'gte' => 'product.retailPriceMin',
                'lte' => 'product.retailPriceMax',
                'gteMessage' => 'lighthouse.validation.errors.store_product.retail_price.min',
                'lteMessage' => 'lighthouse.validation.errors.store_product.retail_price.max',
                'invalidMessage' => 'lighthouse.validation.errors.store_product.retail_price.invalid',
            )),
            new ClassMoneyRange(array(
                'field' => 'retailPrice',
                'gte' => 'product.purchasePrice',
                'gteMessage' => 'lighthouse.validation.errors.product.retailPrice.purchasePrice'
            ))
        );
        $retailMarkupConstraints = array(
            new Precision(array(
                'message' => 'lighthouse.validation.errors.store_product.retail_price.precision',
            )),
            new Range(
                array(
                    'gte' => 0,
                    'gteMessage' => 'lighthouse.validation.errors.store_product.retail_markup.min'
                )
            )
        );
        $retailMarkupClassConstraints = array(
            new ClassNumericRange(array(
                'field' => 'retailMarkup',
                'gte' => 'product.retailMarkupMin',
                'lte' => 'product.retailMarkupMax',
                'gteMessage' => 'lighthouse.validation.errors.store_product.retail_markup.min',
                'lteMessage' => 'lighthouse.validation.errors.store_product.retail_markup.max',
            ))
        );

        switch ($value->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                $retailPriceValid = $this->validateValue(
                    $value->retailPrice,
                    $retailPriceConstraints,
                    'retailPrice'
                );
                $retailPriceClassValid = $this->chainValidateValue(
                    $value,
                    $retailPriceClassConstraints
                );
                if ($retailPriceValid && $retailPriceClassValid) {
                    $this->context->validateValue(
                        $value->retailMarkup,
                        $retailMarkupConstraints,
                        'retailMarkup'
                    );
                    $this->context->validateValue(
                        $value,
                        $retailMarkupClassConstraints
                    );
                }
                break;
            case Product::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $retailMarkupValid = $this->validateValue(
                    $value->retailMarkup,
                    $retailMarkupConstraints,
                    'retailMarkup'
                );
                $retailMarkupClassValid = $this->validateValue(
                    $value,
                    $retailMarkupClassConstraints
                );
                if ($retailMarkupValid && $retailMarkupClassValid) {
                    $this->context->validateValue(
                        $value->retailPrice,
                        $retailPriceConstraints,
                        'retailPrice'
                    );
                    $this->chainValidateValue(
                        $value,
                        $retailPriceClassConstraints
                    );
                }
                break;
        }

        if (!$this->isEmpty($value->roundedRetailPrice)) {
            if ($value->roundedRetailPrice->getCount() <= 0) {
                $this->context->addViolationAt(
                    'retailPrice',
                    $constraint->invalidRoundedRetailPriceMessage
                );
            }
        }
    }
}
