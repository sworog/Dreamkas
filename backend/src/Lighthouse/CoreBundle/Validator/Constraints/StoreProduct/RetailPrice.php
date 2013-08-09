<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\StoreProduct;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RetailPrice extends Constraint
{
    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
