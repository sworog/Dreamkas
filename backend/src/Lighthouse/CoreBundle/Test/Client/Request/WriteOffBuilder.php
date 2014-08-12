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
     * @param string $storeId
     */
    public function __construct($date = null, $storeId = null)
    {
        $this->setDate($date);
        if ($storeId) {
            $this->setStoreId($storeId);
        }
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
     * @param string $storeId
     */
    public function setStoreId($storeId)
    {
        $this->data['store'] = $storeId;
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

    /**
     * @param int $index
     * @return $this
     */
    public function removeProduct($index)
    {
        unset($this->data['products'][$index]);
        return $this;
    }

    /**
     * @param array $mergeData
     * @return array
     */
    public function toArray($mergeData = array())
    {
        return $mergeData + $this->data;
    }

    /**
     * @param string $date
     * @param string $storeId
     * @return WriteOffBuilder
     */
    public static function create($date = null, $storeId = null)
    {
        return new self($date, $storeId);
    }
}
