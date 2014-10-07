<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\ReturnProduct;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReturnProductQuantity extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.return_product.quantity.not_valid';

    public $messageAllReturned = 'lighthouse.validation.errors.return_product.quantity.all_returned';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
