<?php

namespace Lighthouse\CoreBundle\Document\Product;

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
