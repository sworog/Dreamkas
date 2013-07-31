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
    public function getReasonQuantity();

    /**
     * @return Money
     */
    public function getReasonPrice();

    /**
     * @return boolean
     */
    public function increaseAmount();
}
