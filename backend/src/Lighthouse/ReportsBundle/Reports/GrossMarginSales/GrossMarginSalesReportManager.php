<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\CatalogManager;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup\GrossMarginSalesCatalogGroupRepository;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use DateTime;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups\GrossMarginSalesByCatalogGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products\GrossMarginSalesByProductsCollection;

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
     * @var GrossMarginSalesCatalogGroupRepository
     */
    protected $grossMarginSalesCatalogGroupRepository;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CatalogManager
     */
    protected $catalogManager;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @DI\InjectParams({
     *      "grossMarginSalesProductRepository"
     *          = @DI\Inject("lighthouse.reports.document.gross_margin_sales.product.repository"),
     *      "grossMarginSalesCatalogGroupRepository"
     *          = @DI\Inject("lighthouse.reports.document.gross_margin_sales.catalog_group.repository"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "catalogManager" = @DI\Inject("lighthouse.core.document.catalog.manager"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     *
     * @param GrossMarginSalesProductRepository $grossMarginSalesProductRepository
     * @param GrossMarginSalesCatalogGroupRepository $grossMarginSalesCatalogGroupRepository
     * @param StoreProductRepository $storeProductRepository
     * @param ProductRepository $productRepository
     * @param CatalogManager $catalogManager
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        GrossMarginSalesProductRepository $grossMarginSalesProductRepository,
        GrossMarginSalesCatalogGroupRepository $grossMarginSalesCatalogGroupRepository,
        StoreProductRepository $storeProductRepository,
        ProductRepository $productRepository,
        CatalogManager $catalogManager,
        NumericFactory $numericFactory
    ) {
        $this->grossMarginSalesProductRepository = $grossMarginSalesProductRepository;
        $this->grossMarginSalesCatalogGroupRepository = $grossMarginSalesCatalogGroupRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->productRepository = $productRepository;
        $this->catalogManager = $catalogManager;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @return int
     */
    public function recalculateGrossMarginSalesProductReport()
    {
        return $this->grossMarginSalesProductRepository->recalculate();
    }

    /**
     * @return int
     */
    public function recalculateGrossMarginSalesCatalogGroupReport()
    {
        return $this->grossMarginSalesCatalogGroupRepository->recalculate();
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
        $products = $this->productRepository->findBySubCategory($subCategory);
        $storeProducts = $this->storeProductRepository->findOrCreateByStoreIdSubCategory($storeId, $subCategory);

        $reports = $this->getProductReportsByStoreProducts($storeProducts->getIds(), $startDate, $endDate);

        return $this->fillReportsByProducts($reports, $products);
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
        $products = $this->productRepository->findBySubCategory($subCategory);
        $storeProducts = $this->storeProductRepository->findBySubCategory($subCategory);

        $reports = $this->getProductReportsByStoreProducts($storeProducts->getIds(), $startDate, $endDate);

        return $this->fillReportsByProducts($reports, $products);
    }

    /**
     * @param array|string[] $storeProductIds
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return GrossMarginSalesByProductsCollection
     */
    protected function getProductReportsByStoreProducts($storeProductIds, DateTime $startDate, DateTime $endDate)
    {
        $reports = $this
            ->grossMarginSalesProductRepository
            ->findByStoreProductsAndPeriod($storeProductIds, $startDate, $endDate);

        $collection = new GrossMarginSalesByProductsCollection();

        foreach ($reports as $report) {
            $grossMarginSalesByProductReport = $collection->getByProduct($report->storeProduct->product);
            $grossMarginSalesByProductReport->storeProduct = $report->storeProduct;
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

    /**
     * @param GrossMarginSalesByProductsCollection $reports
     * @param $products
     * @return GrossMarginSalesByProductsCollection
     */
    protected function fillReportsByProducts(
        GrossMarginSalesByProductsCollection $reports,
        $products
    ) {
        foreach ($products as $product) {
            if (!$reports->containsProduct($product)) {
                $grossMarginSalesByProductReport = $reports->getByProduct($product);
                $grossMarginSalesByProductReport->grossSales = $this->numericFactory->createMoney(0);
                $grossMarginSalesByProductReport->costOfGoods = $this->numericFactory->createMoney(0);
                $grossMarginSalesByProductReport->grossMargin = $this->numericFactory->createMoney(0);
                $grossMarginSalesByProductReport->quantity = $this->numericFactory->createQuantity(0);
            }
        }

        return $reports;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $storeId
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    public function getCatalogGroupsReports(DateTime $startDate, DateTime $endDate, $storeId = null)
    {
        $catalogGroups = $this->catalogManager->getCatalogGroups();

        $reports = $this->getCatalogGroupReports($startDate, $endDate, $storeId);

        return $reports->fillByCatalogGroups($catalogGroups);
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @param string $storeId
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    protected function getCatalogGroupReports(DateTime $dateFrom, DateTime $dateTo, $storeId = null)
    {
        $reports = $this->grossMarginSalesCatalogGroupRepository->findByPeriod($dateFrom, $dateTo, $storeId);

        $collection = new GrossMarginSalesByCatalogGroupsCollection($this->numericFactory);

        foreach ($reports as $report) {
            $grossMarginSalesByProductReport = $collection->getByCatalogGroup($report->subCategory);
            $grossMarginSalesByProductReport->addReportValues($report);
        }

        return $collection;
    }
}
