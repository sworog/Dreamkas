<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;

/**
 * @property Store $store
 * @property Money $inventoryCostOfGoods
 * @property Sale  $sale
 */
class StoreFirstStart extends AbstractDocument
{
    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Money
     */
    protected $inventoryCostOfGoods;

    /**
     * @var Sale
     */
    protected $sale;
}
