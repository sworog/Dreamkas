<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use DateTime;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts\GrossMarginSalesByProductsCollection;
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
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @DI\InjectParams({
     *      "grossMarginSalesProductRepository"
     *          = @DI\Inject("lighthouse.reports.document.gross_margin_sales.product.repository"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory"),
     * })
     *
     * @param GrossMarginSalesProductRepository $grossMarginSalesProductRepository
     * @param StoreProductRepository $storeProductRepository
     * @param ProductRepository $productRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        GrossMarginSalesProductRepository $grossMarginSalesProductRepository,
        StoreProductRepository $storeProductRepository,
        ProductRepository $productRepository,
        NumericFactory $numericFactory
    ) {
        $this->grossMarginSalesProductRepository = $grossMarginSalesProductRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->productRepository = $productRepository;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param OutputInterface $output
     * @param int $batch
     * @return int
     */
    public function recalculateGrossMarginSalesProductReport(OutputInterface $output = null, $batch = 5000)
    {
        return $this->grossMarginSalesProductRepository->recalculate($output, $batch);
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

        $reports = $this->getReportsByStoreProducts($storeProducts->getIds(), $startDate, $endDate);

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

        $reports = $this->getReportsByStoreProducts($storeProducts->getIds(), $startDate, $endDate);

        return $this->fillReportsByProducts($reports, $products);
    }

    /**
     * @param array|string[] $storeProductIds
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return GrossMarginSalesByProductsCollection
     */
    protected function getReportsByStoreProducts($storeProductIds, DateTime $startDate, DateTime $endDate)
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
}
