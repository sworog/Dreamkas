<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\Payment\Payment;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @property SaleProduct[]|Collection|PersistentCollection  $products
 * @property Payment $payment
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository"
 * )
 */
class Sale extends Receipt
{
    const TYPE = 'Sale';

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
     * @MongoDB\EmbedOne(
     *   discriminatorField="paymentType",
     *   discriminatorMap={
     *      "cash"="Lighthouse\CoreBundle\Document\Payment\CashPayment",
     *      "bankcard"="Lighthouse\CoreBundle\Document\Payment\BankCardPayment"
     *   },

     * )
     * @var Payment
     */
    protected $payment;

    public function __construct()
    {
        parent::__construct();
        $this->date = new DateTimestamp();
    }

    public function calculateTotals()
    {
        parent::calculateTotals();
        if ($this->payment) {
            $this->payment->calculate($this);
        }
    }
}
