<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;

/**
 * @property Money          $grossSales
 * @property Money          $costOfGoods
 * @property Money          $grossMargin
 * @property Quantity       $quantity
 */
abstract class GrossMarginSalesReport extends AbstractDocument
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
     * @param GrossMarginSales $report
     */
    public function addReportValues(GrossMarginSales $report)
    {
        $this->grossSales = $this->grossSales->add($report->grossSales);
        $this->costOfGoods = $this->costOfGoods->add($report->costOfGoods);
        $this->grossMargin = $this->grossMargin->add($report->grossMargin);
        $this->quantity = $this->quantity->add($report->quantity);
    }

    /**
     * @param Money $grossSales
     * @param Money $costOfGoods
     * @param Money $grossMargin
     * @param Quantity $quantity
     */
    public function setValues(Money $grossSales, Money $costOfGoods, Money $grossMargin, Quantity $quantity)
    {
        $this->grossSales = $grossSales;
        $this->costOfGoods = $costOfGoods;
        $this->grossMargin = $grossMargin;
        $this->quantity = $quantity;
    }

    /**
     * @return object
     */
    abstract public function getItem();
}
