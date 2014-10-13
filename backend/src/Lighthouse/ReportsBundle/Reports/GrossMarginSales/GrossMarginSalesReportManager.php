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
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateGrossMarginSalesProductReport(OutputInterface $output = null)
    {
        return $this->grossMarginSalesProductRepository->recalculate($output);
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateGrossMarginSalesCatalogGroupReport(OutputInterface $output = null)
    {
        return $this->grossMarginSalesCatalogGroupRepository->recalculate($output);
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

        return $reports->fillByProducts($products);
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

        return $reports->fillByProducts($products);
    }

    /**
     * @param array|string[] $storeProductIds
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @return GrossMarginSalesByProductsCollection
     */
    protected function getProductReportsByStoreProducts($storeProductIds, DateTime $dateFrom, DateTime $dateTo)
    {
        $reports = $this->grossMarginSalesProductRepository->findByStoreProductsAndPeriod(
            $storeProductIds,
            $dateFrom,
            $dateTo
        );

        $collection = new GrossMarginSalesByProductsCollection($this->numericFactory);

        foreach ($reports as $report) {
            $grossMarginSalesByProducts = $collection->getByProduct($report->storeProduct->product);
            $grossMarginSalesByProducts->storeProduct = $report->storeProduct;
            $grossMarginSalesByProducts->addReportValues($report);
        }

        return $collection;
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @param string $storeId
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    public function getCatalogGroupsReports(DateTime $dateFrom, DateTime $dateTo, $storeId = null)
    {
        $reports = $this->grossMarginSalesCatalogGroupRepository->findByPeriod($dateFrom, $dateTo, $storeId);

        $reportsCollection = new GrossMarginSalesByCatalogGroupsCollection($this->numericFactory);

        foreach ($reports as $report) {
            $reportsCollection->addReportValues($report);
        }

        $catalogGroups = $this->catalogManager->getCatalogGroups();
        return $reportsCollection->fillByCatalogGroups($catalogGroups);
    }
}
