<?php

namespace Lighthouse\CoreBundle\Test\Client\Request;

use DateTime;

class SupplierReturnBuilder
{
    /**
     * @var array
     */
    protected $data = array(
        'date' => null,
        'paid' => false,
        'supplier' => null,
        'products' => array(),
    );

    /**
     * @param string $date
     * @param string $storeId
     * @param string $supplierId
     * @param bool $paid
     */
    public function __construct($date = null, $storeId = null, $supplierId = null, $paid = false)
    {
        $this->setDate($date);
        if ($storeId) {
            $this->setStoreId($storeId);
        }
        if ($supplierId) {
            $this->setSupplierId($supplierId);
        }
        if ($paid) {
            $this->setPaid($paid);
        }
    }

    /**
     * @param string $date
     * @return SupplierReturnBuilder
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
     * @param string $supplierId
     */
    public function setSupplierId($supplierId)
    {
        $this->data['supplier'] = $supplierId;
    }

    /**
     * @param bool $paid
     */
    public function setPaid($paid)
    {
        $this->data['paid'] = $paid;
    }

    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return SupplierReturnBuilder
     */
    public function addProduct($productId, $quantity = 1.0, $price = 5.99)
    {
        $this->data['products'][] = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price
        );
        return $this;
    }

    /**
     * @param int $index
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return $this
     */
    public function setProduct($index, $productId, $quantity = 1.0, $price = 5.99)
    {
        $this->data['products'][$index] = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price
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
     * @param string $supplierId
     * @param bool $paid
     * @return SupplierReturnBuilder
     */
    public static function create($date = null, $storeId = null, $supplierId = null, $paid = false)
    {
        return new self($date, $storeId, $supplierId, $paid);
    }
}
