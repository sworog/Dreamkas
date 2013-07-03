<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Types\Money;

/**
 * Сальдовая ведомость
 *
 * @property string $id
 * @property float  $beginningBalance
 * @property Money  $beginningBalanceMoney
 * @property float  $endingBalance
 * @property Money  $endingBalanceMoney
 * @property float  $quantity
 * @property Money  $totalPrice
 * @property Money  $price
 * @property \DateTime $createdDate
 * @property Product $product
 * @property Reasonable $reason
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository"
 * )
 */
class TrialBalance extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Начальное сальдо
     * @MongoDB\Float
     * @var float
     */
    protected $beginningBalance;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $beginningBalanceMoney;

    /**
     * Конечное сальдо
     * @MongoDB\Float
     * @var float
     */
    protected $endingBalance;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $endingBalanceMoney;

    /**
     * Количество
     * @MongoDB\Int
     * @var float
     */
    protected $quantity;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * Стоимость единицы товара
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var Product
     */
    protected $product;

    /**
     * Основание
     * @MongoDB\ReferenceOne(
     *      discriminatorField="reasonType",
     *      discriminatorMap={
     *          "invoiceProduct"="Lighthouse\CoreBundle\Document\InvoiceProduct\InvoiceProduct",
     *          "purchaseProduct"="Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct",
     *          "writeOffProduct"="Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct"
     *      }
     * )
     * @var Invoice
     */
    protected $reason;

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalPrice()
    {
        $this->totalPrice = new Money();
        $this->totalPrice->setCountByQuantity($this->price, abs($this->quantity));
    }
}
