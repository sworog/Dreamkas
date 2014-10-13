<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\CatalogManager;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup\GrossMarginSalesCatalogGroupRepository;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
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
     * @param GrossMarginSalesFilter $filter
     * @param SubCategory $catalogGroup
     * @return GrossMarginSalesByProductsCollection
     */
    public function getProductsReports(GrossMarginSalesFilter $filter, SubCategory $catalogGroup)
    {
        $reports = $this->grossMarginSalesProductRepository->findByFilterCatalogGroup($filter, $catalogGroup);

        $reportsCollection = new GrossMarginSalesByProductsCollection($this->numericFactory);

        foreach ($reports as $report) {
            $reportsCollection->addReportValues($report);
        }

        $products = $this->productRepository->findBySubCategory($catalogGroup);
        return $reportsCollection->fillByProducts($products);
    }

    /**
     * @param GrossMarginSalesFilter $filter
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    public function getCatalogGroupsReports(GrossMarginSalesFilter $filter)
    {
        $reports = $this->grossMarginSalesCatalogGroupRepository->findByFilter($filter);

        $reportsCollection = new GrossMarginSalesByCatalogGroupsCollection($this->numericFactory);

        foreach ($reports as $report) {
            $reportsCollection->addReportValues($report);
        }

        $catalogGroups = $this->catalogManager->getCatalogGroups();
        return $reportsCollection->fillByCatalogGroups($catalogGroups);
    }
}
