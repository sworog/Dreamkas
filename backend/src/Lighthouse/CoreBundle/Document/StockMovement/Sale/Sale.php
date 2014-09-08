<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @property SaleProduct[]|Collection|PersistentCollection  $products
 * @property Money $amountTendered
 * @property Money $change
 * @property string $paymentType
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository"
 * )
 */
class Sale extends Receipt
{
    const TYPE = 'Sale';

    const PAYMENT_TYPE_CASH = 'cash';
    const PAYMENT_TYPE_BANKCARD = 'bankcard';

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.stock_movement.products.empty")
     * @Assert\Valid(traverse=true)
     * @var SaleProduct[]|Collection
     */
    protected $products;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Choice({Sale::PAYMENT_TYPE_CASH, Sale::PAYMENT_TYPE_BANKCARD})
     * @var string
     */
    protected $paymentType;

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true)
     * @var Money
     */
    protected $amountTendered;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $change;

    public function calculateTotals()
    {
        parent::calculateTotals();
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $this->change = $this->amountTendered->sub($this->sumTotal);
    }
}
