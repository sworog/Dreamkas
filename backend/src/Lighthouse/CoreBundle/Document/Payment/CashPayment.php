<?php

namespace Lighthouse\CoreBundle\Document\Payment;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Types\Numeric\Money;

/**
 * @MongoDB\EmbeddedDocument
 *
 * @property Money $amountTendered
 * @property Money $change
 */
class CashPayment extends Payment
{
    const TYPE = 'cash';

    /**
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
        $this->change = $this->amountTendered->sub($sale->sumTotal);
    }
}
