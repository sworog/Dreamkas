<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Types\Money;

interface Productable
{
    /**
     * @return Product
     */
    public function getReasonProduct();

    /**
     * @return float
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
