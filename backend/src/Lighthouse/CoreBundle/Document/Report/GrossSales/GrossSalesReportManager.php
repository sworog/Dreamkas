<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales\DayGrossSales;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales\GrossSales;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts\GrossSalesByProduct;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts\GrossSalesByProductsCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores\GrossSalesByStoresCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores\StoreGrossSalesByStores;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Product\GrossSalesProductReport;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Product\GrossSalesProductRepository;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReport;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use Lighthouse\CoreBundle\Types\Numeric\Money;

/**
 * @DI\Service("lighthouse.core.document.report.gross_sales.manager")
 */
class GrossSalesReportManager
{
    /**
     * @var StoreGrossSalesRepository
     */
    protected $grossSalesRepository;

    /**
     * @var GrossSalesProductRepository
     */
    protected $grossSalesProductRepository;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @DI\InjectParams({
     *      "grossSalesRepository" = @DI\Inject("lighthouse.core.document.repository.store_gross_sales"),
     *      "grossSalesProductRepository" = @DI\Inject("lighthouse.core.document.repository.product_gross_sales"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product")
     * })
     * @param StoreGrossSalesRepository $grossSalesRepository
     * @param GrossSalesProductRepository $grossSalesProductRepository
     * @param StoreRepository $storeRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param ProductRepository $productRepository
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        StoreGrossSalesRepository $grossSalesRepository,
        GrossSalesProductRepository $grossSalesProductRepository,
        StoreRepository $storeRepository,
        TrialBalanceRepository $trialBalanceRepository,
        ProductRepository $productRepository,
        StoreProductRepository $storeProductRepository
    ) {
        $this->grossSalesRepository = $grossSalesRepository;
        $this->grossSalesProductRepository = $grossSalesProductRepository;
        $this->storeRepository = $storeRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->productRepository = $productRepository;
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @param DateTime $time
     * @return GrossSales
     */
    public function getGrossSales(DateTime $time = null)
    {
        $intervals = array(
            'yesterday' => '-1 day 23:00',
            'twoDaysAgo' => '-2 days 23:00',
            'eightDaysAgo' => '-8 days 23:00',
        );
        $dates = $this->getDates($time, $intervals);
        $storeDayReports = $this->grossSalesRepository->findByDates($dates);
        $grossSales = $this->createGrossSales($storeDayReports, $dates);
        $this->fillGrossSales($grossSales, $dates);
        return $grossSales;
    }

    /**
     * @param Cursor|StoreGrossSalesReport[] $storeDayReports
     * @param DateTimestamp[] $dates
     * @return GrossSales
     */
    protected function createGrossSales(Cursor $storeDayReports, array $dates)
    {
        $grossSales = new GrossSales();
        foreach ($storeDayReports as $storeDayReport) {
            foreach ($dates as $key => $date) {
                if ($date->equals($storeDayReport->dayHour)) {
                    if (!isset($grossSales->$key)) {
                        $grossSales->$key = new DayGrossSales($date);
                    }
                    /* @var DayGrossSales $dayGrossSales */
                    $dayGrossSales = $grossSales->$key;
                    $dayGrossSales->addRunningSum($storeDayReport->runningSum);
                    $dayGrossSales->addHourSum($storeDayReport->hourSum);
                }
            }
        }
        return $grossSales;
    }

    /**
     * @param GrossSales $grossSales
     * @param DateTimestamp[] $dates
     */
    protected function fillGrossSales(GrossSales $grossSales, array $dates)
    {
        foreach ($dates as $key => $date) {
            if (!isset($grossSales->$key)) {
                $grossSales->$key = new DayGrossSales($date);
            }
        }
    }

    /**
     * @param DateTime|string $time
     * @return GrossSalesByStoresCollection
     */
    public function getGrossSalesByStores(DateTime $time = null)
    {
        $intervals = array(
            'yesterday' => '-1 day 23:00',
            'twoDaysAgo' => '-2 days 23:00',
            'eightDaysAgo' => '-8 days 23:00',
        );
        $dates = $this->getDates($time, $intervals);
        $storeDayReports = $this->grossSalesRepository->findByDates($dates);
        $storeDayReports->sort(array('store' => 1));
        /* @var Store[]|Cursor $stores */
        $stores = $this->storeRepository->findAll();
        $stores->sort(array('id' => 1));
        $storeReports = $this->createGrossSalesByStoresCollection($storeDayReports, $dates);
        $this->fillGrossSalesByStoresCollection($storeReports, $stores, $dates);
        return $storeReports;
    }

    /**
     * @param DateTime|string $time
     * @param array $intervals
     * @return DateTimestamp[]
     */
    protected function getDates($time, array $intervals)
    {
        $dateTime = new DateTimestamp($time);
        $dates = array();
        foreach ($intervals as $key => $interval) {
            $nextDateTime = clone $dateTime;
            $dates[$key] = $nextDateTime->modify($interval);
        }
        return $dates;
    }

    /**
     * @param DateTime|string|null $time
     * @param array $intervals
     * @return DateTimestamp[]
     */
    protected function getDayHours($time, array $intervals)
    {
        $dateTime = new DateTimestamp($time);
        $dateTime->setMinutes(0)->setSeconds(0);
        $dayHours = array();
        foreach ($intervals as $key => $interval) {
            $nextDateTime = clone $dateTime;
            if (null !== $interval) {
                $nextDateTime->modify($interval);
            }
            for ($hour = 0; $hour <= $nextDateTime->getHours(); $hour++) {
                $nextDayHour = clone $nextDateTime;
                $dayHours[$key][$hour] = $nextDayHour->setHours($hour);
            }
        }
        return $dayHours;
    }

    /**
     * @param Cursor|StoreGrossSalesReport[] $storeDayReports
     * @param DateTimestamp[] $dates
     * @return StoreGrossSalesByStores[]|GrossSalesByStoresCollection
     */
    protected function createGrossSalesByStoresCollection(Cursor $storeDayReports, array $dates)
    {
        $storeReports = new GrossSalesByStoresCollection();
        /* @var StoreGrossSalesReport $storeDayReport */
        foreach ($storeDayReports as $storeDayReport) {
            $storeReport = $storeReports->getByStore($storeDayReport->store);
            foreach ($dates as $key => $date) {
                if ($date->equals($storeDayReport->dayHour)) {
                    $storeReport->$key = $storeDayReport;
                }
            }
        }
        return $storeReports;
    }

    /**
     * @param GrossSalesByStoresCollection $grossSalesByStores
     * @param Cursor|Store[] $stores
     * @param DateTime[] $dates
     */
    protected function fillGrossSalesByStoresCollection(
        GrossSalesByStoresCollection $grossSalesByStores,
        Cursor $stores,
        array $dates
    ) {
        foreach ($stores as $store) {
            $storeReport = $grossSalesByStores->getByStore($store);
            foreach ($dates as $key => $date) {
                if (!isset($storeReport->$key)) {
                    $storeReport->$key = $this->grossSalesRepository->createByDayHourAndStore($date, $store);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function recalculateStoreGrossSalesReport()
    {
        $results = $this->trialBalanceRepository->calculateGrossSales();
        $dm = $this->grossSalesRepository->getDocumentManager();
        foreach ($results as $result) {
            $storeId = $result['_id']['store'];
            $day = DateTimestamp::createFromMongoDate($result['_id']['day']);
            foreach ($result['value'] as $hour => $grossSales) {
                $dayHour = clone $day;
                $dayHour->setTime($hour, 0);
                $report = $this->grossSalesRepository->createByDayHourAndStoreId(
                    $dayHour,
                    (string) $storeId,
                    new Money($grossSales['runningSum']),
                    new Money($grossSales['hourSum'])
                );
                $dm->persist($report);
            }
        }
        $dm->flush();

        return count($results);
    }

    /**
     * @return int
     */
    public function recalculateGrossSalesProductReport()
    {
        $stores = $this->storeRepository->findAll()->toArray();
        $countProducts = $this->productRepository->findAll()->count();

        $results = $this->trialBalanceRepository->calculateGrossSalesProduct($stores, $countProducts);

        $dm = $this->grossSalesProductRepository->getDocumentManager();

        foreach ($results as $result) {
            $storeProductId = $result['_id']['storeProduct'];
            $year = $result['_id']['year'];
            $month = $result['_id']['month'];
            $day = $result['_id']['day'];
            $hour = $result['_id']['hour'];
            $dayHour = $this->createUTCDateByYMDH($year, $month, $day, $hour);
            $report = $this->grossSalesProductRepository->createByDayHourAndStoreProductId(
                $dayHour,
                $storeProductId,
                null,
                new Money($result['hourSum'])
            );
            $dm->persist($report);
        }

        $dm->flush();

        return count($results);
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @return DateTimestamp
     */
    protected function createUTCDateByYMDH($year, $month, $day, $hour)
    {
        $dateString = sprintf(
            "%d-%d-%dT%d:00:00Z",
            $year,
            $month,
            $day,
            $hour
        );

        return new DateTimestamp($dateString);
    }

    /**
     * @param Store $store
     * @param SubCategory $subCategory
     * @param DateTime|null $time
     * @return GrossSalesByProduct[]
     */
    public function getGrossSalesByProducts(Store $store, SubCategory $subCategory, DateTime $time = null)
    {
        $intervals = array(
            'today' => null,
            'yesterday' => '-1 days',
            'weekAgo' => '-7 days',
        );

        $dayHours = $this->getDayHours($time, $intervals);
        $storeProducts = $this->getStoreProductsByStoreSubCategory($store, $subCategory);

        $reports = $this->grossSalesProductRepository->findByDayHoursStoreProducts($dayHours, $storeProducts);
        $grossSalesByProductCollection = $this->createGrossSalesByProductsCollection($reports, $dayHours);
        $this->fillGrossSalesByProductsCollection(
            $grossSalesByProductCollection,
            $storeProducts,
            $dayHours
        );

        return $grossSalesByProductCollection->getValues();
    }

    /**
     * @param Store $store
     * @param SubCategory $subCategory
     * @return StoreProductCollection
     */
    protected function getStoreProductsByStoreSubCategory(Store $store, SubCategory $subCategory)
    {
        return $this->storeProductRepository->findByStoreSubCategory($store, $subCategory);
    }

    /**
     * @param Cursor $reports
     * @param DateTimestamp[] $dayHours
     * @return GrossSalesByProductsCollection
     */
    protected function createGrossSalesByProductsCollection(Cursor $reports, array $dayHours)
    {
        $endDayHours = $this->extractEndDayHours($dayHours);
        $collection = new GrossSalesByProductsCollection();
        /** @var GrossSalesProductReport $report */
        foreach ($reports as $report) {
            $storeProductId = $report->product->id;
            if (null === $collection->get($storeProductId)) {
                $collection->set($storeProductId, new GrossSalesByProduct($report->product, $endDayHours));
            }
            foreach ($endDayHours as $dayName => $dayHour) {
                if ($dayHour->equalsDate($report->dayHour)) {
                    $collection->get($storeProductId)->$dayName->addRunningSum($report->hourSum);
                    break;
                }
            }
        }

        return $collection;
    }

    /**
     * @param GrossSalesByProductsCollection $collection
     * @param StoreProductCollection $storeProducts
     * @param DateTime[] $dayHours
     * @return GrossSalesByProductsCollection
     */
    public function fillGrossSalesByProductsCollection(
        GrossSalesByProductsCollection $collection,
        StoreProductCollection $storeProducts,
        array $dayHours
    ) {
        $endDayHours = $this->extractEndDayHours($dayHours);

        foreach ($storeProducts as $storeProduct) {
            $storeProductId = $storeProduct->id;
            if (null === $collection->get($storeProductId)) {
                $collection->set($storeProductId, new GrossSalesByProduct($storeProduct, $endDayHours));
            }
        }

        return $collection;
    }

    /**
     * @param DateTimestamp[] $dayHours
     * @return DateTimestamp[]
     */
    protected function extractEndDayHours(array $dayHours)
    {
        $endDayHours = array();
        foreach ($dayHours as $key => $dayHoursArray) {
            foreach ($dayHoursArray as $dayHour) {
                if (!isset ($endDayHours[$key])
                    || $endDayHours[$key] < $dayHour
                ) {
                    $endDayHours[$key] = $dayHour;
                }
            }
        }

        return $endDayHours;
    }
}
