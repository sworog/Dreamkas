<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Document\Invoice;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\InvoiceProductRepository"
 * )
 */
class InvoiceProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Количество
     * @MongoDB\Int
     * @var int
     */
    protected $quantity;

    /**
     * Закупочная цена
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * Сумма
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Invoice", simple=true)
     * @Assert\NotBlank
     * @var Invoice
     */
    protected $invoice;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Product", simple=true)
     * @Assert\NotBlank
     * @var Product
     */
    protected $product;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'quantity' => $this->quantity,
        );
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalPrice()
    {
        if (null === $this->totalPrice) {
            $this->totalPrice = new Money();
        }
        $this->totalPrice->setCount($this->price->getCount() * $this->quantity);
    }
}
