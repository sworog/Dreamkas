<?php

namespace Lighthouse\CoreBundle\Test\Client\Request;

class WriteOffBuilder extends StockMovementBuilder
{
    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $cause
     * @return WriteOffBuilder
     */
    public function addProduct($productId, $quantity = 1.0, $price = 5.99, $cause = 'Порча')
    {
        $this->data['products'][] = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'cause' => $cause
        );
        return $this;
    }

    /**
     * @param int $index
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $cause
     * @return $this
     */
    public function setProduct($index, $productId, $quantity = 1.0, $price = 5.99, $cause = 'Порча')
    {
        $this->data['products'][$index] = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'cause' => $cause
        );
        return $this;
    }
}
