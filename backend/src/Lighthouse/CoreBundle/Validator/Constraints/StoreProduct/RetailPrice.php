<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\StoreProduct;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RetailPrice extends Constraint
{
    /**
     * @var string
     */
    public $invalidRoundedRetailPriceMessage = 'lighthouse.validation.errors.store_product.rounded_retail_price.invalid';
    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
