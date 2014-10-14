<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;

abstract class GrossMarginSalesReportCollection extends DocumentCollection
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function __construct(NumericFactory $numericFactory)
    {
        parent::__construct();

        $this->numericFactory = $numericFactory;
    }

    /**
     * @param object $item
     * @return bool
     */
    public function containsItem($item)
    {
        return $this->containsKey($item->id);
    }

    /**
     * @param object $item
     * @return GrossMarginSalesReport
     */
    public function getByItem($item)
    {
        if ($this->containsItem($item)) {
            return $this->get($item->id);
        } else {
            return $this->addEmptyReportByItem($item);
        }
    }

    /**
     * @param object $item
     * @return GrossMarginSalesReport
     */
    protected function addEmptyReportByItem($item)
    {
        $report = $this->createByItem($item);
        $this->addEmptyReport($report);
        return $report;
    }

    /**
     * @param GrossMarginSalesReport $report
     * @return GrossMarginSalesReport
     */
    protected function addEmptyReport(GrossMarginSalesReport $report)
    {
        $report->setValues(
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createMoney(0),
            $this->numericFactory->createQuantity(0)
        );
        $this->set($report->getItem()->id, $report);
        return $report;
    }

    /**
     * @param object $item
     * @return GrossMarginSalesReport
     */
    abstract public function createByItem($item);

    /**
     * @param array $items
     * @return $this
     */
    public function fillByItems($items)
    {
        foreach ($items as $item) {
            if (!$this->containsItem($item)) {
                $this->addEmptyReportByItem($item);
            }
        }
        return $this;
    }

    /**
     * @param GrossMarginSales $report
     */
    public function addReportValues(GrossMarginSales $report)
    {
        $this->getByItem($report->getItem())->addReportValues($report);
    }
}
