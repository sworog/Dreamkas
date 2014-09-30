<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @property StoreProduct   $storeProduct
 * @property Money          $grossSales
 * @property Money          $costOfGoods
 * @property Money          $grossMargin
 * @property Quantity       $quantity
 */
class GrossMarginSalesByProduct extends AbstractDocument
{
    /**
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @var Money
     */
    protected $grossSales;

    /**
     * @var Money
     */
    protected $costOfGoods;

    /**
     * @var Money
     */
    protected $grossMargin;

    /**
     * @var Quantity
     */
    protected $quantity;

    /**
     * @param StoreProduct $storeProduct
     */
    public function __construct(StoreProduct $storeProduct)
    {
        $this->storeProduct = $storeProduct;
    }
}
