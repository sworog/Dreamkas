<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;

/**
 * @property Store $store
 * @property Money $costOfGoods
 * @property Sale  $sale
 */
class FirstStartStore
{
    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Money
     */
    protected $costOfGoods;

    /**
     * @var Sale
     */
    protected $sale;
}
