<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BarcodeUnique extends Constraint
{
    /**
     * @var string
     */
    public $outerMessage = 'lighthouse.validation.errors.product.barcode.unique.outer';

    /**
     * @var string
     */
    public $innerMessage = 'lighthouse.validation.errors.product.barcode.unique.inner';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'product_barcode_unique_validator';
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
