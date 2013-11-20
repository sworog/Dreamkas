<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\InvoiceProduct;

use Symfony\Component\Validator\Constraint;

class Vat extends Constraint
{
    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
