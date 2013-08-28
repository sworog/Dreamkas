<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RetailPrice extends Constraint
{
    /**
     * @var string
     */
    public $notBlankRetailPriceMessage = 'lighthouse.validation.errors.product.retailPrice.not_blank';

    /**
     * @var string
     */
    public $notBlankRetailMarkupMessage = 'lighthouse.validation.errors.product.retailMarkup.not_blank';

    /**
     * @var string
     */
    public $compareRetailMarkupMessage = 'lighthouse.validation.errors.product.retailMarkup.compare';

    /**
     * @var string
     */
    public $compareRetailPriceMessage = 'lighthouse.validation.errors.product.retailPrice.compare';

    /**
     * @var string
     */
    public $retailPriceGtePurchasePriceMessage = 'lighthouse.validation.errors.product.retailPrice.purchasePrice';

    /**
     * @var string
     */
    public $retailMarkupGteZeroMessage = 'lighthouse.validation.errors.product.retailMarkup.range';

    /**
     * @var string
     */
    public $blankRetailPriceMessage = 'lighthouse.validation.errors.product.retailPrice.blank';

    /**
     * @var string
     */
    public $blankRetailMarkupMessage = 'lighthouse.validation.errors.product.retailMarkup.blank';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
