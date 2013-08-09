<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\StoreProduct;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Precision;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassMoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassNumericRange;
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
                'lte' => 'product.retailPriceMax'
            ))
        );
        $retailMarkupConstraints = array(
            new Precision(),
        );
        $retailMarkupClassConstraints = array(
            new ClassNumericRange(array(
                'field' => 'retailMarkup',
                'gte' => 'product.retailMarkupMin',
                'lte' => 'product.retailMarkupMax'
            ))
        );

        switch ($value->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                $retailPriceValid = $this->validateValue(
                    $value->retailPrice,
                    $retailPriceConstraints,
                    'retailPrice'
                );
                $retailPriceClassValid = $this->validateValue(
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
                    $this->context->validateValue(
                        $value,
                        $retailPriceClassConstraints
                    );
                }
                break;
        }
    }
}
