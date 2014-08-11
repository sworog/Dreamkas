<?php

namespace Lighthouse\CoreBundle\Test\Client\Request;

use DateTime;

class WriteOffBuilder
{
    /**
     * @var array
     */
    protected $data = array(
        'date' => null,
        'products' => array(),
    );

    /**
     * @param string $date
     */
    public function __construct($date = null)
    {
        $this->setDate($date);
    }

    /**
     * @param string $date
     * @return WriteOffBuilder
     */
    public function setDate($date = null)
    {
        $this->data['date'] = $date ?: date(DateTime::W3C);
        return $this;
    }

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
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param string $date
     * @return WriteOffBuilder
     */
    public static function create($date = null)
    {
        return new self($date);
    }
}
