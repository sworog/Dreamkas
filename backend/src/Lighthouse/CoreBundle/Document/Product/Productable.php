<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Types\Quantity;

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
