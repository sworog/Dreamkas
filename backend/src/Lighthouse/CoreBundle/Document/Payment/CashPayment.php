<?php

namespace Lighthouse\CoreBundle\Document\Payment;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Range\ClassMoneyRange as AssertClassMoneyRange;
use Lighthouse\CoreBundle\Validator\Constraints\Money as AssertMoney;

/**
 * @MongoDB\EmbeddedDocument
 *
 * @property Money $amountTendered
 * @property Money $change
 *
 * @AssertClassMoneyRange(
 *      field="amountTendered",
 *      gte="sale.sumTotal",
 *      gteMessage="lighthouse.validation.errors.payment.cash.amountTendered"
 * )
 */
class CashPayment extends Payment
{
    const TYPE = 'cash';

    /**
     * @AssertMoney(notBlank=true,zero=true)
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $amountTendered;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $change;

    /**
     * @param Sale $sale
     */
    public function calculate(Sale $sale)
    {
        if ($this->amountTendered instanceof Money) {
            $this->change = $this->amountTendered->sub($sale->sumTotal);
        }
    }
}
