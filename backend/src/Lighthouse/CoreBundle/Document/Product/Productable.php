<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

interface Productable
{
    /**
     * @return Product
     */
    public function getReasonProduct();

    /**
     * @return Quantity
     */
    public function getProductQuantity();

    /**
     * @return Money
     */
    public function getProductPrice();

    /**
     * @return boolean
     */
    public function increaseAmount();
}
