<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Money;
use DateTime;

/**
 * Сальдовая ведомость
 *
 * @property string         $id
 * @property float          $beginningBalance
 * @property Money          $beginningBalanceMoney
 * @property float          $endingBalance
 * @property Money          $endingBalanceMoney
 * @property float          $quantity
 * @property Money          $totalPrice
 * @property Money          $price
 * @property DateTime       $createdDate
 * @property StoreProduct   $storeProduct
 * @property Reasonable     $reason
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
     * @var DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Store\StoreProduct",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var Store
     */
    protected $store;

    /**
     * Основание
     * @MongoDB\ReferenceOne(
     *      discriminatorField="reasonType",
     *      discriminatorMap={
     *          "invoiceProduct"="Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct",
     *          "saleProduct"="Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct",
     *          "returnProduct"="Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct",
     *          "writeOffProduct"="Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct"
     *      }
     * )
     * @var Reasonable
     */
    protected $reason;

    public function __construct()
    {
        $this->createdDate = new DateTime;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalPrice()
    {
        $this->totalPrice = $this->price->mul(abs($this->quantity));
        $this->store = $this->storeProduct->store;
    }
}
