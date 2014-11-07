<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
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
     * @param NumericFactory $numericFactory
     */
    public function setEmptyValues(NumericFactory $numericFactory)
    {
        $this->grossSales = $numericFactory->createMoney(0);
        $this->costOfGoods = $numericFactory->createMoney(0);
        $this->grossMargin = $numericFactory->createMoney(0);
        $this->quantity = $numericFactory->createQuantity(0);
    }

    /**
     * @return object
     */
    abstract public function getItem();
}
