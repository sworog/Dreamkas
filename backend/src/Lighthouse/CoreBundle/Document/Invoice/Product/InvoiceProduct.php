<?php

namespace Lighthouse\CoreBundle\Document\Invoice\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @property string     $id
 * @property Quantity   $quantity
 * @property Money      $priceEntered
 * @property Money      $price
 * @property Money      $priceWithoutVAT
 * @property Money      $totalPrice
 * @property Money      $totalPriceWithoutVAT
 * @property Money      $amountVAT
 * @property DateTime   $acceptanceDate
 * @property Money      $totalAmountVAT
 * @property Invoice    $invoice
 * @property ProductVersion $product
 * @property Store      $store
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository"
 * )
 */
class InvoiceProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'InvoiceProduct';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Chain(
     *      constraints={@LighthouseAssert\Precision(3), @LighthouseAssert\Range\Range(gt=0)},
     *      groups={"Default", "products"}
     * )
     * @var Quantity
     */
    protected $quantity;

    /**
     * Введённая цена
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true, groups={"Default", "products"})
     * @var Money
     */
    protected $priceEntered;

    /**
     * Закупочная цена
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * Закупочная цена без НДС
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $priceWithoutVAT;

    /**
     * Сумма
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * Сумма без НДС
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPriceWithoutVAT;

    /**
     * НДС в деньгах
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $amountVAT;

    /**
     * Сумма НДС
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalAmountVAT;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $acceptanceDate;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Invoice\Invoice",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Assert\NotBlank
     * @Serializer\MaxDepth(2)
     * @var Invoice
     */
    protected $invoice;

    /**
     * @Assert\NotBlank(groups={"Default", "products"})
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\MaxDepth(3)
     * @var ProductVersion
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Exclude
     * @var Product
     */
    protected $originalProduct;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Exclude
     * @var Store
     */
    protected $store;

    /**
     * @MongoDB\PreFlush
     */
    public function beforeSave()
    {
        $this->calculateTotals();

        $this->acceptanceDate = $this->invoice->acceptanceDate;
        $this->store = $this->invoice->store;
        $this->originalProduct = $this->product->getObject();
    }

    public function calculateTotals()
    {
        if ($this->price && $this->priceWithoutVAT && $this->amountVAT) {
            $this->setTotalPrice($this->price->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
            $this->setTotalPriceWithoutVAT($this->priceWithoutVAT->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
            $this->setTotalAmountVAT($this->amountVAT->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
        }
    }

    /**
     * @param Money|Decimal $totalPrice
     */
    public function setTotalPrice(Money $totalPrice = null)
    {
        if (null === $this->totalPrice || !$this->totalPrice->equals($totalPrice)) {
            $this->totalPrice = $totalPrice;
        }
    }

    /**
     * @param Money|Decimal $totalPriceWithoutVAT
     */
    public function setTotalPriceWithoutVAT(Money $totalPriceWithoutVAT = null)
    {
        if (null === $this->totalPriceWithoutVAT || !$this->totalPriceWithoutVAT->equals($totalPriceWithoutVAT)) {
            $this->totalPriceWithoutVAT = $totalPriceWithoutVAT;
        }
    }

    /**
     * @param Money|Decimal $totalAmountVAT
     */
    public function setTotalAmountVAT(Money $totalAmountVAT = null)
    {
        if (null === $this->totalAmountVAT || !$this->totalAmountVAT->equals($totalAmountVAT)) {
            $this->totalAmountVAT = $totalAmountVAT;
        }
    }

    /**
     * @return string
     */
    public function getReasonId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getReasonType()
    {
        return self::REASON_TYPE;
    }

    /**
     * @return \DateTime
     */
    public function getReasonDate()
    {
        return $this->invoice->acceptanceDate;
    }

    /**
     * @return Quantity
     */
    public function getProductQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return Product
     */
    public function getReasonProduct()
    {
        return $this->product->getObject();
    }

    /**
     * @return Money
     */
    public function getProductPrice()
    {
        return $this->price;
    }

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return true;
    }

    /**
     * @return Storeable
     */
    public function getReasonParent()
    {
        return $this->invoice;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity = null)
    {
        $this->quantity = $quantity;
    }

    /**
     * Workaround to recalc price by VAT
     * @param Money $priceEntered
     */
    public function setPriceEntered(Money $priceEntered)
    {
        $this->priceEntered = $priceEntered;
        $this->calculatePrices();
    }

    /**
     * Workaround to recalc price by VAT
     * @param Invoice $invoice
     */
    public function setInvoice(Invoice $invoice = null)
    {
        $this->invoice = $invoice;
        $this->calculatePrices();
    }

    public function calculatePrices()
    {
        // Если продукт не найден, то не сичтаем ничего
        // TODO: Подумать над изменением
        if (null === $this->product || null === $this->invoice || null === $this->priceEntered) {
            return;
        }

        $decimalVAT = Decimal::createFromNumeric($this->product->vat * 0.01, 2);
        if ($this->invoice->includesVAT) {
            // Расчёт цены без НДС из цены с НДС
            $this->price = clone $this->priceEntered;
            $this->priceWithoutVAT = $this->price->div($decimalVAT->add(1), Decimal::ROUND_HALF_EVEN);
            $this->amountVAT = $this->priceWithoutVAT->sub($this->price->toString())->invert();
        } else {
            // Расчёт цены с НДС из цены без НДС
            $this->priceWithoutVAT = clone $this->priceEntered;
            $this->price = $this->priceWithoutVAT->mul($decimalVAT->add(1), Decimal::ROUND_HALF_EVEN);
            $this->amountVAT = $this->priceWithoutVAT->mul($decimalVAT, Decimal::ROUND_HALF_EVEN);
        }
    }
}
