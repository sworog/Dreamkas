<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Network;

use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Network\GrossMarginSalesNetwork;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;

class GrossMarginSalesByNetwork extends GrossMarginSalesReport
{
    /**
     * @param NumericFactory $numericFactory
     * @param GrossMarginSalesNetwork[] $dayReports
     */
    public function __construct(NumericFactory $numericFactory, $dayReports)
    {
        $this->setEmptyValues($numericFactory);
        $this->addDayReports($dayReports);
    }

    /**
     * @param GrossMarginSalesNetwork[] $dayReports
     */
    public function addDayReports($dayReports)
    {
        foreach ($dayReports as $dayReport) {
            $this->addReportValues($dayReport);
        }
    }

    /**
     * @return object
     */
    public function getItem()
    {
        return null;
    }
}
