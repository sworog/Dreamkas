<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * Сальдовая ведомость
 *
 * @property string         $id
 * @property float          $beginningBalance
 * @property Money          $beginningBalanceMoney
 * @property float          $endingBalance
 * @property Money          $endingBalanceMoney
 * @property Quantity       $startIndex
 * @property Quantity       $endIndex
 * @property int            $processingStatus
 * @property Quantity       $quantity
 * @property Money          $costOfGoods
 * @property Money          $totalPrice
 * @property Money          $price
 * @property DateTime       $createdDate
 * @property StoreProduct   $storeProduct
 * @property Reasonable     $reason
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository"
 * )
 *
 * @MongoDB\Indexes({
 *      @MongoDB\Index(keys={
 *          "reason.$ref"="asc",
 *          "createdDate.date"="asc",
 *          "storeProduct"="asc"
 *      })
 * })
 */
class TrialBalance extends AbstractDocument
{
    const PROCESSING_STATUS_OK = 0;
    const PROCESSING_STATUS_UNPROCESSED = 1;

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
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $startIndex;

    /**
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $endIndex;

    /**
     * @MongoDB\Float
     * @MongoDB\Index
     * @var integer
     */
    protected $processingStatus = self::PROCESSING_STATUS_UNPROCESSED;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @var float
     */
    protected $quantity;

    /**
     * @MongoDB\Field(type="money")
     * @var Money::
     */
    protected $costOfGoods;

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
     * @MongoDB\Field(type="datetime_tz")
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
        $this->totalPrice = $this->price->mul($this->quantity);
        $this->store = $this->storeProduct->store;
    }
}
