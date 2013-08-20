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
     * @var string
     */
    public $retailPriceForbiddenMessage = 'lighthouse.validation.errors.store_product.retail_price.forbidden';

    /**
     * @var string
     */
    public $retailMarkupForbiddenMessage = 'lighthouse.validation.errors.store_product.retail_markup.forbidden';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
