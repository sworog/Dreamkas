<?php

namespace Lighthouse\CoreBundle\Test\Client\Request;

class InvoiceBuilder extends SupplierReturnBuilder
{
    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return $this
     */
    public function addProduct($productId, $quantity = 1.0, $price = 5.99)
    {
        $this->data['products'][] = array(
            'product' => $productId,
            'quantity' => $quantity,
            'priceEntered' => $price
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
            'priceEntered' => $price
        );
        return $this;
    }

    /**
     * @param string $accepter
     * @return $this
     */
    public function setAccepter($accepter)
    {
        $this->data['accepter'] = $accepter;
        return $this;
    }

    /**
     * @param string $supplierInvoiceNumber
     * @return $this
     */
    public function setSupplierInvoiceNumber($supplierInvoiceNumber)
    {
        $this->data['supplierInvoiceNumber'] = $supplierInvoiceNumber;
        return $this;
    }

    /**
     * @param string $legalEntity
     * @return $this
     */
    public function setLegalEntity($legalEntity)
    {
        $this->data['legalEntity'] = $legalEntity;
        return $this;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setIncludesVAT($flag = true)
    {
        $this->data['includesVAT'] = $flag;
        return $this;
    }

    /**
     * @param string $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->data['order'] = $order;
        return $this;
    }
}
