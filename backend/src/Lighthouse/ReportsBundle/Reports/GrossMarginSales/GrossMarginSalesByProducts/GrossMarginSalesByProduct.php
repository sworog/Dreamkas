<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @property StoreProduct   $storeProduct
 * @property Product        $product
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
     * @var Product
     */
    protected $product;

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
     * @param Product|StoreProduct $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
