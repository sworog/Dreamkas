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
     * @param GrossMarginSales[]|null $reports
     * @param object[]|array|null $items
     */
    public function __construct(NumericFactory $numericFactory, $reports = null, $items = null)
    {
        parent::__construct();

        $this->numericFactory = $numericFactory;

        if ($reports) {
            $this->fillByReports($reports);
        }

        if ($items) {
            $this->fillByItems($items);
        }
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
        $report->setEmptyValues($this->numericFactory);
        $this->set($report->getItem()->id, $report);
        return $report;
    }

    /**
     * @param object $item
     * @return GrossMarginSalesReport
     */
    abstract public function createByItem($item);

    /**
     * @param object[]|array $items
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
     * @param GrossMarginSales[] $reports
     */
    public function fillByReports($reports)
    {
        foreach ($reports as $report) {
            $this->addReportValues($report);
        }
    }

    /**
     * @param GrossMarginSales $report
     */
    public function addReportValues(GrossMarginSales $report)
    {
        $this->getByItem($report->getItem())->addReportValues($report);
    }
}
