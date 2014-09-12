<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Invoice;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property Money      $priceEntered
 * @property Money      $priceWithoutVAT
 * @property Money      $totalPriceWithoutVAT
 * @property Money      $amountVAT
 * @property Money      $totalAmountVAT
 * @property Invoice    $parent
 *
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class InvoiceProduct extends StockMovementProduct
{
    const TYPE = 'InvoiceProduct';

    /**
     * Введённая цена
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true, groups={"Default", "products"})
     * @MongoDB\Field(type="money")
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
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Assert\NotBlank
     * @Serializer\MaxDepth(2)
     * @var Invoice
     */
    protected $parent;

    /**
     * @MongoDB\PreFlush
     */
    public function beforeSave()
    {
        parent::beforeSave();
    }

    /**
     * @return Money
     */
    public function calculateTotals()
    {
        if ($this->price && $this->priceWithoutVAT && $this->amountVAT) {
            $this->setTotalPrice($this->price->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
            $this->setTotalPriceWithoutVAT($this->priceWithoutVAT->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
            $this->setTotalAmountVAT($this->amountVAT->mul($this->quantity, Decimal::ROUND_HALF_EVEN));
        }

        return $this->totalPrice;
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
     * @return boolean
     */
    public function increaseAmount()
    {
        return true;
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
     * @param StockMovement|Invoice $parent
     */
    public function setReasonParent(StockMovement $parent)
    {
        $this->setParent($parent);
    }

    /**
     * Workaround to recalc price by VAT
     * @param Invoice $invoice
     */
    public function setParent(Invoice $invoice = null)
    {
        $this->parent = $invoice;
        $this->calculatePrices();
    }

    public function calculatePrices()
    {
        // Если продукт не найден, то не сичтаем ничего
        // TODO: Подумать над изменением
        if (null === $this->product || null === $this->parent || null === $this->priceEntered) {
            return;
        }

        $decimalVAT = Decimal::createFromNumeric($this->product->vat * 0.01, 2);
        if ($this->parent->includesVAT) {
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
