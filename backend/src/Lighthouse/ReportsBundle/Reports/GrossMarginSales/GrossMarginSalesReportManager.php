<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use Doctrine\ODM\MongoDB\Cursor;
use DateTime;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts\GrossMarginSalesByProduct;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts\GrossMarginSalesByProductsCollection;

/**
 * @DI\Service("lighthouse.reports.gross_margin_sales.manager")
 */
class GrossMarginSalesReportManager
{
    /**
     * @var GrossMarginSalesProductRepository
     */
    protected $grossMarginSalesProductRepository;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *      "grossMarginSalesProductRepository"
     *          = @DI\Inject("lighthouse.reports.document.gross_margin_sales.product.repository"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     * })
     *
     * @param GrossMarginSalesProductRepository $grossMarginSalesProductRepository
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        GrossMarginSalesProductRepository $grossMarginSalesProductRepository,
        StoreProductRepository $storeProductRepository
    ) {
        $this->grossMarginSalesProductRepository = $grossMarginSalesProductRepository;
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @return int
     */
    public function recalculateGrossMarginSalesProductReport()
    {
        return $this->grossMarginSalesProductRepository->recalculate();
    }

    /**
     * @param Store $store
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Cursor
     */
    public function getGrossSalesByProductReports(Store $store, DateTime $startDate, DateTime $endDate)
    {
        $storeProducts = $this->storeProductRepository->findByStore($store);

        $reports = $this->grossMarginSalesProductRepository->findByStoreProductsAndPeriod(
            $storeProducts->getIds(),
            $startDate,
            $endDate
        );

        $collection = new GrossMarginSalesByProductsCollection();

        foreach ($reports as $report) {
            $grossMarginSalesByProductReport = $collection->getByStoreProduct($report->product);
            $grossMarginSalesByProductReport->grossSales
                = $report->grossSales->add($grossMarginSalesByProductReport->grossSales);
            $grossMarginSalesByProductReport->costOfGoods
                = $report->costOfGoods->add($grossMarginSalesByProductReport->costOfGoods);
            $grossMarginSalesByProductReport->grossMargin
                = $report->grossMargin->add($grossMarginSalesByProductReport->grossMargin);
            $grossMarginSalesByProductReport->quantity
                = $report->quantity->add($grossMarginSalesByProductReport->quantity);
        }

        return $collection;
    }
}
