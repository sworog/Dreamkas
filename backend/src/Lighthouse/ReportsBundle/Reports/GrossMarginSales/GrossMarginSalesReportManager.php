<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductReport;
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
     * @var ProductRepository
     */
    protected $productRepository;

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
     * @param SubCategory $subCategory
     * @param $storeId
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return GrossMarginSalesByProductsCollection
     */
    public function getGrossSalesByProductForStoreReports(
        SubCategory $subCategory,
        $storeId,
        DateTime $startDate,
        DateTime $endDate
    ) {
        $storeProduct = $this->storeProductRepository->findByStoreIdSubCategory($storeId, $subCategory);

        $reports = $this
            ->grossMarginSalesProductRepository
            ->findByStoreProductsAndPeriod($storeProduct->getIds(), $startDate, $endDate);

        $collection = new GrossMarginSalesByProductsCollection();

        return $this->fillGrossMarginSalesByProductCollection($collection, $reports);
    }

    /**
     * @param SubCategory $subCategory
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return GrossMarginSalesByProductsCollection
     */
    public function getGrossSalesByProductForSubCategoryReports(
        SubCategory $subCategory,
        DateTime $startDate,
        DateTime $endDate
    ) {
        return array();
    }

    /**
     * @param GrossMarginSalesByProductsCollection $collection
     * @param GrossMarginSalesProductReport[]|Cursor $reports
     * @return GrossMarginSalesByProductsCollection
     */
    public function fillGrossMarginSalesByProductCollection(GrossMarginSalesByProductsCollection $collection, $reports)
    {
        foreach ($reports as $report) {
            $grossMarginSalesByProductReport = $collection->getByProduct($report->product->product);
            $grossMarginSalesByProductReport->storeProduct = $report->product;
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
