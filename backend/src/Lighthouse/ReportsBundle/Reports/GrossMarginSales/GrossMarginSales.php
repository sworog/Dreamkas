<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesReport;

/**
 * @property Money          $grossSales
 * @property Money          $costOfGoods
 * @property Money          $grossMargin
 * @property Quantity       $quantity
 */
abstract class GrossMarginSales extends AbstractDocument
{
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
     * @param GrossMarginSalesReport $report
     */
    public function addReportValues(GrossMarginSalesReport $report)
    {
        $this->grossSales = $this->grossSales->add($report->grossSales);
        $this->costOfGoods = $this->costOfGoods->add($report->costOfGoods);
        $this->grossMargin = $this->grossMargin->add($report->grossMargin);
        $this->quantity = $this->quantity->add($report->grossMargin);
    }
}
