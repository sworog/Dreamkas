<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\CatalogManager;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup\GrossMarginSalesCatalogGroupRepository;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Store\GrossMarginSalesStoreRepository;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups\GrossMarginSalesByCatalogGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products\GrossMarginSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Stores\GrossMarginSalesByStoresCollection;
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
     * @var GrossMarginSalesStoreRepository
     */
    protected $grossMarginSalesStoreRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CatalogManager
     */
    protected $catalogManager;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

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
     *      "grossMarginSalesStoreRepository"
     *          = @DI\Inject("lighthouse.reports.document.gross_margin_sales.store.repository"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "catalogManager" = @DI\Inject("lighthouse.core.document.catalog.manager"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     *
     * @param GrossMarginSalesProductRepository $grossMarginSalesProductRepository
     * @param GrossMarginSalesCatalogGroupRepository $grossMarginSalesCatalogGroupRepository
     * @param GrossMarginSalesStoreRepository $grossMarginSalesStoreRepository
     * @param ProductRepository $productRepository
     * @param CatalogManager $catalogManager
     * @param StoreRepository $storeRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        GrossMarginSalesProductRepository $grossMarginSalesProductRepository,
        GrossMarginSalesCatalogGroupRepository $grossMarginSalesCatalogGroupRepository,
        GrossMarginSalesStoreRepository $grossMarginSalesStoreRepository,
        ProductRepository $productRepository,
        CatalogManager $catalogManager,
        StoreRepository $storeRepository,
        NumericFactory $numericFactory
    ) {
        $this->grossMarginSalesProductRepository = $grossMarginSalesProductRepository;
        $this->grossMarginSalesCatalogGroupRepository = $grossMarginSalesCatalogGroupRepository;
        $this->grossMarginSalesStoreRepository = $grossMarginSalesStoreRepository;
        $this->productRepository = $productRepository;
        $this->catalogManager = $catalogManager;
        $this->storeRepository = $storeRepository;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateProductReport(OutputInterface $output = null)
    {
        return $this->grossMarginSalesProductRepository->recalculate($output);
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateCatalogGroupReport(OutputInterface $output = null)
    {
        return $this->grossMarginSalesCatalogGroupRepository->recalculate($output);
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateStoreReport(OutputInterface $output = null)
    {
        return $this->grossMarginSalesStoreRepository->recalculate($output);
    }

    /**
     * @param GrossMarginSalesFilter $filter
     * @param SubCategory $catalogGroup
     * @return GrossMarginSalesByProductsCollection
     */
    public function getProductsReports(GrossMarginSalesFilter $filter, SubCategory $catalogGroup)
    {
        $reports = $this->grossMarginSalesProductRepository->findByFilterCatalogGroup($filter, $catalogGroup);
        $products = $this->productRepository->findBySubCategory($catalogGroup);
        return new GrossMarginSalesByProductsCollection(
            $this->numericFactory,
            $reports,
            $products
        );
    }

    /**
     * @param GrossMarginSalesFilter $filter
     * @return GrossMarginSalesByCatalogGroupsCollection
     */
    public function getCatalogGroupsReports(GrossMarginSalesFilter $filter)
    {
        $reports = $this->grossMarginSalesCatalogGroupRepository->findByFilter($filter);
        $catalogGroups = $this->catalogManager->getCatalogGroups();
        return new GrossMarginSalesByCatalogGroupsCollection(
            $this->numericFactory,
            $reports,
            $catalogGroups
        );
    }

    /**
     * @param GrossMarginSalesFilter $filter
     * @return GrossMarginSalesByStoresCollection
     */
    public function getStoreReports(GrossMarginSalesFilter $filter)
    {
        $reports = $this->grossMarginSalesStoreRepository->findByFilter($filter);
        $stores = $this->storeRepository->findAll();
        return new GrossMarginSalesByStoresCollection(
            $this->numericFactory,
            $reports,
            $stores
        );
    }
}
