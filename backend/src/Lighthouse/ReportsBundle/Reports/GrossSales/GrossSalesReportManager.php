<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Category\CategoryRepository;
use Lighthouse\CoreBundle\Document\Classifier\ParentableRepository;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\Category\GrossSalesCategoryRepository;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\GrossSalesNodeReport;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\GrossSalesNodeRepository;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSales\DayGrossSales;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSales\GrossSales;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByCategories\GrossSalesByCategoriesCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByGroups\GrossSalesByGroupsCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByProducts\GrossSalesByProductsCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores\GrossSalesByStoresReport;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesBySubCategories\GrossSalesBySubCategoriesCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores\GrossSalesByStoresCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores\StoreGrossSalesByStores;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\Group\GrossSalesGroupRepository;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesStoreToday\GrossSalesStoreTodayReport;
use Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductReport;
use Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductRepository;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\SubCategory\GrossSalesSubCategoryRepository;
use Lighthouse\ReportsBundle\Document\GrossSales\Store\GrossSalesStoreReport;
use Lighthouse\ReportsBundle\Document\GrossSales\Store\GrossSalesStoreRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use Closure;

/**
 * @DI\Service("lighthouse.reports.gross_sales.manager")
 */
class GrossSalesReportManager
{
    /**
     * @var GrossSalesStoreRepository::
     */
    protected $grossSalesStoreRepository;

    /**
     * @var GrossSalesProductRepository
     */
    protected $grossSalesProductRepository;

    /**
     * @var GrossSalesSubCategoryRepository::
     */
    protected $grossSalesSubCategoryRepository;

    /**
     * @var GrossSalesCategoryRepository
     */
    protected $grossSalesCategoryRepository;

    /**
     * @var GrossSalesGroupRepository
     */
    protected $grossSalesGroupRepository;

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
     * @var SubCategoryRepository
     */
    protected $subCategoryRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var GrossSalesProductReport[]
     */
    protected $scheduledGrossSalesProductReportsToSave = array();

    /**
     * @DI\InjectParams({
     *      "grossSalesStoreRepository" = @DI\Inject("lighthouse.reports.document.gross_sales.store.repository"),
     *      "grossSalesProductRepository" = @DI\Inject("lighthouse.reports.document.gross_sales.product.repository"),
     *      "grossSalesSubCategoryRepository" =
     *      @DI\Inject("lighthouse.reports.document.gross_sales.subcategory.repository"),
     *      "grossSalesCategoryRepository" = @DI\Inject("lighthouse.reports.document.gross_sales.category.repository"),
     *      "grossSalesGroupRepository" = @DI\Inject("lighthouse.reports.document.gross_sales.group.repository"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     *      "subCategoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.subcategory"),
     *      "categoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.category"),
     *      "groupRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.group"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory"),
     * })
     * @param GrossSalesStoreRepository $grossSalesStoreRepository
     * @param GrossSalesProductRepository $grossSalesProductRepository
     * @param GrossSalesSubCategoryRepository $grossSalesSubCategoryRepository
     * @param GrossSalesCategoryRepository $grossSalesCategoryRepository
     * @param GrossSalesGroupRepository $grossSalesGroupRepository
     * @param StoreRepository $storeRepository
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param ProductRepository $productRepository
     * @param StoreProductRepository $storeProductRepository
     * @param SubCategoryRepository $subCategoryRepository
     * @param CategoryRepository $categoryRepository
     * @param GroupRepository $groupRepository
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        GrossSalesStoreRepository $grossSalesStoreRepository,
        GrossSalesProductRepository $grossSalesProductRepository,
        GrossSalesSubCategoryRepository $grossSalesSubCategoryRepository,
        GrossSalesCategoryRepository $grossSalesCategoryRepository,
        GrossSalesGroupRepository $grossSalesGroupRepository,
        StoreRepository $storeRepository,
        TrialBalanceRepository $trialBalanceRepository,
        ProductRepository $productRepository,
        StoreProductRepository $storeProductRepository,
        SubCategoryRepository $subCategoryRepository,
        CategoryRepository $categoryRepository,
        GroupRepository $groupRepository,
        NumericFactory $numericFactory
    ) {
        $this->grossSalesStoreRepository = $grossSalesStoreRepository;
        $this->grossSalesProductRepository = $grossSalesProductRepository;
        $this->grossSalesSubCategoryRepository = $grossSalesSubCategoryRepository;
        $this->grossSalesCategoryRepository = $grossSalesCategoryRepository;
        $this->grossSalesGroupRepository = $grossSalesGroupRepository;
        $this->storeRepository = $storeRepository;
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->productRepository = $productRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->groupRepository = $groupRepository;
        $this->numericFactory = $numericFactory;
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
        $dates = $this->getDatesForDay($time, $intervals);
        $queryDates = $this->getQueryDates($dates);
        $storeDayReports = $this->grossSalesStoreRepository->findByDates($queryDates);
        $grossSales = $this->createGrossSales($storeDayReports, $dates);
        $this->fillGrossSales($grossSales, $dates);
        return $grossSales;
    }

    /**
     * @param Cursor|GrossSalesStoreReport[] $storeDayReports
     * @param array $dates
     * @return GrossSales
     */
    protected function createGrossSales(Cursor $storeDayReports, array $dates)
    {
        $grossSales = new GrossSales();
        foreach ($storeDayReports as $storeDayReport) {
            foreach ($dates as $key => $dayHours) {
                $endDayHour = end($dayHours);
                reset($dayHours);
                if ($endDayHour->equalsDate($storeDayReport->dayHour)) {
                    if (!isset($grossSales->$key)) {
                        $grossSales->$key = new DayGrossSales($endDayHour);
                    }
                    /* @var DayGrossSales $dayGrossSales */
                    $dayGrossSales = $grossSales->$key;
                    $dayGrossSales->addRunningSum($storeDayReport->hourSum);
                }
            }
        }
        return $grossSales;
    }

    /**
     * @param GrossSales $grossSales
     * @param array $dates
     */
    protected function fillGrossSales(GrossSales $grossSales, array $dates)
    {
        foreach ($dates as $key => $dayHours) {
            $endDayHour = end($dayHours);
            reset($dayHours);
            if (!isset($grossSales->$key)) {
                $grossSales->$key = new DayGrossSales($endDayHour);
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
        $dates = $this->getDatesForDay($time, $intervals);
        $queryDates = $this->getQueryDates($dates);
        $storeDayReports = $this->grossSalesStoreRepository->findByDates($queryDates);
        $storeDayReports->sort(array('store' => 1));
        /* @var Store[]|Cursor $stores */
        $stores = $this->storeRepository->findAll();
        $stores->sort(array('id' => 1));
        $storeReports = $this->createGrossSalesByStoresCollection($storeDayReports, $dates);
        $this->fillGrossSalesByStoresCollection($storeReports, $stores, $dates);
        return $storeReports;
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
     * @param DateTime|string|null $time
     * @param array $intervals
     * @return DateTimestamp[]
     */
    public function getDatesForDay($time, array $intervals)
    {
        $dateTime = new DateTimestamp($time);
        $dateTime->setMinutes(0)->setSeconds(0);
        $dayHours = array();
        foreach ($intervals as $key => $interval) {
            $nextDateTime = clone $dateTime;
            if (null !== $interval) {
                $nextDateTime->modify($interval);
            }
            for ($hour = 0; $hour <= 23; $hour++) {
                $nextDayHour = clone $nextDateTime;
                $dayHours[$key][$hour] = $nextDayHour->setHours($hour);
            }
        }
        return $dayHours;
    }

    /**
     * @param Cursor|GrossSalesStoreReport[] $storeDayReports
     * @param array $dates
     * @return StoreGrossSalesByStores[]|GrossSalesByStoresCollection
     */
    protected function createGrossSalesByStoresCollection(Cursor $storeDayReports, array $dates)
    {
        $storeReports = new GrossSalesByStoresCollection();
        foreach ($storeDayReports as $storeDayReport) {
            $storeReport = $storeReports->getByStore($storeDayReport->store);
            foreach ($dates as $key => $dayHours) {
                $firstDayHour = current($dayHours);
                if ($firstDayHour->equalsDate($storeDayReport->dayHour)) {
                    if (null === $storeReport->$key) {
                        $storeReportDayHour = clone $firstDayHour;
                        $storeReportDayHour->setHours(23);
                        $storeReport->$key = new GrossSalesByStoresReport($storeReportDayHour);
                    }
                    $storeReport->$key->addRunningSum($storeDayReport->hourSum);
                }
            }
        }
        return $storeReports;
    }

    /**
     * @param GrossSalesByStoresCollection $grossSalesByStores
     * @param Cursor|Store[] $stores
     * @param array $dates
     */
    protected function fillGrossSalesByStoresCollection(
        GrossSalesByStoresCollection $grossSalesByStores,
        Cursor $stores,
        array $dates
    ) {
        foreach ($stores as $store) {
            $storeReport = $grossSalesByStores->getByStore($store);
            foreach ($dates as $key => $dayHours) {
                if (!isset($storeReport->$key)) {
                    $endDayHour = end($dayHours);
                    reset($dayHours);
                    $storeReport->$key = new GrossSalesByStoresReport($endDayHour);
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
        $dm = $this->grossSalesStoreRepository->getDocumentManager();
        foreach ($results as $result) {
            $storeId = $result['_id']['store'];
            $day = $this->createUTCDateByYMDH(
                $result['_id']['year'],
                $result['_id']['month'],
                $result['_id']['day'],
                $result['_id']['hour']
            );
            $report = $this->grossSalesStoreRepository->createByDayHourAndStoreId(
                $day,
                (string) $storeId,
                new Money($result['hourSum'])
            );
            $dm->persist($report);
        }
        $dm->flush();

        return count($results);
    }

    /**
     * @param Store $store
     * @param DateTime $time
     * @return GrossSalesStoreTodayReport
     */
    public function getGrossSalesStoreReport(Store $store, DateTime $time = null)
    {
        $time = new DateTimestamp($time);
        $time->modify('-1 hour');

        $intervals = array(
            'today' => null,
            'yesterday' => '-1 days',
            'weekAgo' => '-7 days',
        );
        $dates = $this->getDatesForDay($time, $intervals);
        $queryDates = $this->getQueryDates($dates);

        $reports = $this->grossSalesStoreRepository->findByStoreDayHours($store, $queryDates);

        $grossSalesStoreTodayReport = $this->createGrossSalesStoreTodayReport($time, $dates, $reports);
        $this->fillGrossSalesStoreTodayReport($grossSalesStoreTodayReport, $time, $dates);
        $this->calculateDiffGrossSalesStoreTodayReport($grossSalesStoreTodayReport, $dates);

        return $grossSalesStoreTodayReport;
    }

    /**
     * @param DateTimestamp $time
     * @param array $dates
     * @param Cursor|GrossSalesStoreReport[] $reports
     * @return GrossSalesStoreTodayReport
     */
    protected function createGrossSalesStoreTodayReport(DateTimestamp $time, array $dates, Cursor $reports)
    {
        $grossSalesStoreTodayReport = new GrossSalesStoreTodayReport($dates);
        $nowHour = $time->getHours();

        foreach ($reports as $report) {
            /** @var DateTimestamp[] $dayHours */
            foreach ($dates as $key => $dayHours) {
                $firstDayHour = current($dayHours);
                if ($firstDayHour->equalsDate($report->dayHour)) {
                    $reportDayHour = new DateTimestamp($report->dayHour);

                    if ($reportDayHour->getHours() <= $nowHour) {
                        $grossSalesStoreTodayReport->$key->now->addValue($report->hourSum);
                    }

                    if (null !== $grossSalesStoreTodayReport->$key->dayEnd) {
                        $grossSalesStoreTodayReport->$key->dayEnd->addValue($report->hourSum);
                    }
                }
            }
        }

        return $grossSalesStoreTodayReport;
    }

    /**
     * @param GrossSalesStoreTodayReport $grossSalesStoreTodayReport
     * @param DateTimestamp $time
     * @param array $dates
     */
    protected function fillGrossSalesStoreTodayReport(
        GrossSalesStoreTodayReport $grossSalesStoreTodayReport,
        DateTimestamp $time,
        array $dates
    ) {
        $nowHour = $time->getHours();
        $endDayHours = $this->extractEndDayHours($dates);
        foreach ($endDayHours as $key => $dayHour) {
            if (null === $grossSalesStoreTodayReport->$key->now->date) {
                $nowDate = clone $dayHour;
                $nowDate->setHours($nowHour);
                // TODO: Из за хитрого вывода дат. Желательно поменять фронт и тут будет норм
                $nowDate->modify("+1 hour");
                $grossSalesStoreTodayReport->$key->now->date = $nowDate;
            }
            if (null !== $grossSalesStoreTodayReport->$key->dayEnd
                && null === $grossSalesStoreTodayReport->$key->dayEnd->date
            ) {
                $dayEndDate = clone $dayHour;
                $dayEndDate->setMinutes(59)->setSeconds(59);
                $grossSalesStoreTodayReport->$key->dayEnd->date = $dayEndDate;
            }
        }
    }

    /**
     * @param GrossSalesStoreTodayReport $grossSalesStoreTodayReport
     * @param array $dates
     */
    protected function calculateDiffGrossSalesStoreTodayReport(
        GrossSalesStoreTodayReport $grossSalesStoreTodayReport,
        array $dates
    ) {
        $datesKeys = array_keys($dates);
        $primaryKey = array_shift($datesKeys);
        /** @var Money $primaryNowValue */
        $primaryNowValue = $grossSalesStoreTodayReport->$primaryKey->now->value;

        if (0 == $primaryNowValue->getCount()) {
            return;
        }

        foreach ($datesKeys as $secondKey) {
            /** @var Money $secondNowValue */
            $secondNowValue = $grossSalesStoreTodayReport->$secondKey->now->value;
            if (0 == $secondNowValue->toNumber()) {
                $diff = 0;
            } else {
                $diff = ($primaryNowValue->toNumber() / $secondNowValue->toNumber() - 1) * 100;
            }
            $grossSalesStoreTodayReport->$secondKey->now->diff = Decimal::createFromNumeric($diff, 2);
        }
    }

    /**
     * @param Store $store
     * @param null|DateTime $time
     * @return TodayHoursGrossSales
     */
    public function getGrossSalesStoreByHours(Store $store, DateTime $time = null)
    {
        $dayHours = $this->getTodayDayHours($time);

        $queryDates = $this->getQueryDates($dayHours);
        $reports = $this->grossSalesStoreRepository->findByStoreDayHours($store, $queryDates);

        $todayHoursGrossSales = $this->createGrossSalesStoreByHoursCollection($reports, $dayHours);

        $this->fillGrossSalesStoreByHoursCollection($todayHoursGrossSales, $dayHours);

        return $todayHoursGrossSales->normalize($dayHours);
    }

    /**
     * @param Cursor $reports
     * @param array $dates
     * @return TodayHoursGrossSales
     */
    protected function createGrossSalesStoreByHoursCollection(Cursor $reports, array $dates)
    {
        $todayHoursGrossSales = new TodayHoursGrossSales($dates);

        foreach ($reports as $report) {
            foreach ($dates as $key => $dayHours) {
                /** @var DateTimestamp $firstDayHour */
                $firstDayHour = current($dayHours);
                if ($firstDayHour->equalsDate($report->dayHour)) {
                    $reportDayHour = new DateTimestamp($report->dayHour);
                    $reportHour = $reportDayHour->getHours();
                    $todayHoursGrossSales->$key->set($reportHour, $report);
                }
            }
        }

        return $todayHoursGrossSales;
    }

    /**
     * @param TodayHoursGrossSales $todayHoursGrossSales
     * @param array $dates
     */
    protected function fillGrossSalesStoreByHoursCollection(TodayHoursGrossSales $todayHoursGrossSales, array $dates)
    {
        foreach ($dates as $key => $dayHours) {
            /** @var DateTimestamp $dayHour */
            foreach ($dayHours as $dayHour) {
                $hour = $dayHour->getHours();
                if (null === $todayHoursGrossSales->$key->get($hour)) {
                    $todayHoursGrossSales->$key->set($hour, $this->createEmptyGrossSalesStoreReport($dayHour));
                }
            }
        }
    }

    /**
     * @param DateTimestamp $dayHour
     * @return GrossSalesStoreReport
     */
    protected function createEmptyGrossSalesStoreReport(DateTimestamp $dayHour)
    {
        $report = new GrossSalesStoreReport();
        $report->dayHour = $dayHour;
        $report->hourSum = new Money(0);
        return $report;
    }

    /**
     * @param int $batch
     * @return int
     */
    public function recalculateGrossSalesProductReport($batch = 1000)
    {
        $stores = $this->storeRepository->findAll();
        $countProducts = $this->productRepository->count();

        $results = $this->trialBalanceRepository->calculateGrossSalesProduct($stores, $countProducts);

        $countResultsSaved = 0;
        $batchCount = 0;
        $batchStartTime = microtime(true);
        $totalSaveTime = 0;
        foreach ($results['reports'] as $reports) {
            foreach ($reports as $reportRAW) {

                $storeProductId = $reportRAW['_id']['storeProduct'];
                $year = $reportRAW['_id']['year'];
                $month = $reportRAW['_id']['month'];
                $day = $reportRAW['_id']['day'];
                $hour = $reportRAW['_id']['hour'];
                $dayHour = $this->createUTCDateByYMDH($year, $month, $day, $hour);

                $report = $this->grossSalesProductRepository->createByDayHourAndStoreProductId(
                    $dayHour,
                    $storeProductId,
                    null,
                    new Money($reportRAW['hourSum']),
                    $this->numericFactory->createQuantityFromCount($reportRAW['hourQuantity'])
                );

                $this->scheduleGrossSalesProductReportToSave($report);

                $countResultsSaved++;
                $batchCount++;
                if (0 == $countResultsSaved % $batch) {
                    $this->saveScheduledReports();
                    $batchDurationTime = microtime(true) - $batchStartTime;
                    $totalSaveTime += $batchDurationTime;
//                    printf(
//                        "Скорость: %6.2f отчётов в секунду, средняя: %6.2f, всего сохранено: %6d, осталось: %6d \n",
//                        $batchCount / $batchDurationTime,
//                        $countResultsSaved / $totalSaveTime,
//                        $countResultsSaved,
//                        $countResults - $countResultsSaved
//                    );
                    $batchCount = 0;
                    $batchStartTime = microtime(true);
                }
            }
        }

        $this->saveScheduledReports();

        return $results['totalCount'];
    }

    /**
     * @param GrossSalesProductReport $report
     */
    protected function scheduleGrossSalesProductReportToSave(GrossSalesProductReport $report)
    {
        $this->scheduledGrossSalesProductReportsToSave[] = $report;
    }

    /**
     *
     */
    protected function saveScheduledReports()
    {
        if (count($this->scheduledGrossSalesProductReportsToSave)) {
            // TODO: подумать об отказе от raw upsert (проверсти тесты скорости) 26.09.14
            $this->grossSalesProductRepository->rawUpsertReports($this->scheduledGrossSalesProductReportsToSave);
            $this->scheduledGrossSalesProductReportsToSave = array();
        }
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
        return DateTimestamp::createUTCFromParts($year, $month, $day, $hour);
    }

    /**
     * @param DateTime $time
     * @return array
     */
    protected function getTodayDayHours(DateTime $time = null)
    {
        $intervals = array(
            'today' => '-1 hour',
            'yesterday' => '-1 days -1 hour',
            'weekAgo' => '-7 days -1 hour',
        );

        return $this->getDayHours($time, $intervals);
    }

    /**
     * @param Store $store
     * @param SubCategory $subCategory
     * @param DateTime|null $time
     * @return GrossSalesByProductsCollection
     */
    public function getGrossSalesByProducts(Store $store, SubCategory $subCategory, DateTime $time = null)
    {
        $dayHours = $this->getTodayDayHours($time);
        $endDayHours = $this->extractEndDayHours($dayHours);
        $queryDates = $this->getQueryDates($dayHours);

        $storeProducts = $this->storeProductRepository->findOrCreateByStoreSubCategory($store, $subCategory);

        $reports = $this->grossSalesProductRepository->findByDayHoursStoreProducts(
            $queryDates,
            $storeProducts->getIds()
        );

        $grossSalesByProductCollection = $this->createGrossSalesByProductsCollection($reports, $endDayHours);

        $this->fillGrossSalesByProductsCollection(
            $grossSalesByProductCollection,
            $storeProducts,
            $endDayHours
        );

        return $grossSalesByProductCollection->sortByName();
    }

    /**
     * @param Cursor $reports
     * @param DateTimestamp[] $endDayHours
     * @return GrossSalesByProductsCollection
     */
    protected function createGrossSalesByProductsCollection(Cursor $reports, array $endDayHours)
    {
        $collection = new GrossSalesByProductsCollection();
        /** @var GrossSalesProductReport $report */
        foreach ($reports as $report) {
            $storeProductReport = $collection->getByStoreProduct($report->product, $endDayHours);
            foreach ($endDayHours as $dayName => $dayHour) {
                if ($dayHour->equalsDate($report->dayHour)) {
                    $storeProductReport->$dayName->addRunningSum($report->hourSum);
                    break;
                }
            }
        }

        return $collection;
    }

    /**
     * @param GrossSalesByProductsCollection $collection
     * @param StoreProductCollection $storeProducts
     * @param DateTime[] $endDayHours
     * @return GrossSalesByProductsCollection
     */
    public function fillGrossSalesByProductsCollection(
        GrossSalesByProductsCollection $collection,
        StoreProductCollection $storeProducts,
        array $endDayHours
    ) {
        foreach ($storeProducts as $storeProduct) {
            if (!$collection->containsStoreProduct($storeProduct)) {
                $collection->createByStoreProduct($storeProduct, $endDayHours);
            }
        }

        return $collection;
    }

    /**
     * @param Store $store
     * @param Category $category
     * @param DateTime|null $time
     * @return GrossSalesBySubCategoriesCollection
     */
    public function getGrossSalesBySubCategories(Store $store, Category $category, DateTime $time = null)
    {
        $cursor = $this->subCategoryRepository->findByParent($category->id);
        $nodes = new DocumentCollection($cursor);

        return $this->getGrossSalesByNode(
            $this->grossSalesSubCategoryRepository,
            $nodes,
            new GrossSalesBySubCategoriesCollection(),
            $store,
            $time
        );
    }

    /**
     * @param Store $store
     * @param Group $group
     * @param DateTime|null $time
     * @return GrossSalesByCategoriesCollection
     */
    public function getGrossSalesByCategories(Store $store, Group $group, DateTime $time = null)
    {
        $cursor = $this->categoryRepository->findByParent($group->id);
        $nodes = new DocumentCollection($cursor);

        return $this->getGrossSalesByNode(
            $this->grossSalesCategoryRepository,
            $nodes,
            new GrossSalesByCategoriesCollection(),
            $store,
            $time
        );
    }

    /**
     * @param Store $store
     * @param DateTime|null $time
     * @return GrossSalesByGroupsCollection
     */
    public function getGrossSalesByGroups(Store $store, DateTime $time = null)
    {
        $cursor = $this->groupRepository->findBy(array(), array('name' => DocumentRepository::SORT_ASC));
        $nodes = new DocumentCollection($cursor);

        return $this->getGrossSalesByNode(
            $this->grossSalesGroupRepository,
            $nodes,
            new GrossSalesByGroupsCollection(),
            $store,
            $time
        );
    }

    /**
     * @param GrossSalesNodeRepository $grossSalesNodeRepository
     * @param DocumentCollection $nodes
     * @param GrossSalesByClassifierNodeCollection $collection
     * @param Store $store
     * @param DateTime $time
     * @return GrossSalesByClassifierNodeCollection
     */
    protected function getGrossSalesByNode(
        GrossSalesNodeRepository $grossSalesNodeRepository,
        DocumentCollection $nodes,
        GrossSalesByClassifierNodeCollection $collection,
        Store $store,
        DateTime $time = null
    ) {
        $dayHours = $this->getTodayDayHours($time);
        $endDayHours = $this->extractEndDayHours($dayHours);
        $queryDates = $this->getQueryDates($dayHours);

        $reports = $grossSalesNodeRepository->findByDayHoursAndNodeIds(
            $queryDates,
            $nodes->getIds(),
            $store->id
        );

        $this->createGrossSalesByClassifierNodeCollection(
            $collection,
            $reports,
            $endDayHours
        );

        $this->fillGrossSalesByClassifierNodeCollection(
            $collection,
            $nodes,
            $endDayHours
        );

        return $collection->sortByName();
    }

    /**
     * @param GrossSalesByClassifierNodeCollection $collection
     * @param Cursor|GrossSalesNodeReport[] $reports
     * @param DateTimestamp[] $endDayHours
     * @return GrossSalesBySubCategoriesCollection
     */
    protected function createGrossSalesByClassifierNodeCollection(
        GrossSalesByClassifierNodeCollection $collection,
        Cursor $reports,
        array $endDayHours
    ) {
        foreach ($reports as $report) {
            $categoryReport = $collection->getByNode($report->getNode(), $endDayHours);
            foreach ($endDayHours as $dayName => $dayHour) {
                if ($dayHour->equalsDate($report->dayHour)) {
                    $categoryReport->$dayName->addRunningSum($report->hourSum);
                    break;
                }
            }
        }

        return $collection;
    }

    /**
     * @param GrossSalesByClassifierNodeCollection $collection
     * @param AbstractNode[]|DocumentCollection $nodes
     * @param DateTime[] $dates
     */
    public function fillGrossSalesByClassifierNodeCollection(
        GrossSalesByClassifierNodeCollection $collection,
        DocumentCollection $nodes,
        array $dates
    ) {
        foreach ($nodes as $node) {
            if (!$collection->containsKey($node->id)) {
                $collection->createByNode($node, $dates);
            }
        }
    }

    /**
     * @param array $dayHours
     * @return DateTimestamp[]
     */
    protected function extractEndDayHours(array $dayHours)
    {
        $endDayHours = array();
        foreach ($dayHours as $key => $dayHoursArray) {
            $endDayHours[$key] = max($dayHoursArray);
        }
        return $endDayHours;
    }

    /**
     * @param array $dayHours
     * @return DateTimestamp[]
     */
    public function getQueryDates(array $dayHours)
    {
        return call_user_func_array('array_merge', $dayHours);
    }

    /**
     * @param int $batch
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateGrossSalesBySubCategories(OutputInterface $output, $batch = 1000)
    {
        $storeProductRepository = $this->storeProductRepository;
        $prepareChildIdsClosure = function (array $childIds, $storeId) use ($storeProductRepository) {
            $storeProductIds = array();
            foreach ($childIds as $childId) {
                $storeProductIds[] = $storeProductRepository->getIdByStoreIdAndProductId(
                    $storeId,
                    $childId
                );
            }
            return $storeProductIds;
        };

        $this->recalculateGrossSalesByNode(
            $this->subCategoryRepository,
            $this->productRepository,
            $this->grossSalesSubCategoryRepository,
            $this->grossSalesProductRepository,
            $output,
            $batch,
            $prepareChildIdsClosure
        );
    }

    /**
     * @param int $batch
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateGrossSalesByCategories(OutputInterface $output, $batch = 1000)
    {
        return $this->recalculateGrossSalesByNode(
            $this->categoryRepository,
            $this->subCategoryRepository,
            $this->grossSalesCategoryRepository,
            $this->grossSalesSubCategoryRepository,
            $output,
            $batch
        );
    }

    /**
     * @param int $batch
     * @param OutputInterface $output
     * @return int
     */
    public function recalculateGrossSalesByGroups(OutputInterface $output, $batch = 1000)
    {
        return $this->recalculateGrossSalesByNode(
            $this->groupRepository,
            $this->categoryRepository,
            $this->grossSalesGroupRepository,
            $this->grossSalesCategoryRepository,
            $output,
            $batch
        );
    }

    /**
     * @param DocumentRepository $nodeRepository
     * @param ParentableRepository $childNodeRepository
     * @param GrossSalesNodeRepository $nodeReportRepository
     * @param GrossSalesCalculatable $childNodeReportRepository
     * @param OutputInterface $output
     * @param int $batch
     * @param callable $prepareChildIdsCallback
     * @return int
     */
    protected function recalculateGrossSalesByNode(
        DocumentRepository $nodeRepository,
        ParentableRepository $childNodeRepository,
        GrossSalesNodeRepository $nodeReportRepository,
        GrossSalesCalculatable $childNodeReportRepository,
        OutputInterface $output,
        $batch = 1000,
        Closure $prepareChildIdsCallback = null
    ) {
        $dotHelper = new DotHelper($output);

        $nodeIds = $nodeRepository->findAllIds();
        $storeIds = $this->storeRepository->findAllIds();

        $dm = $nodeReportRepository->getDocumentManager();
        $i = 0;

        foreach ($nodeIds as $nodeId) {
            $childIds = $childNodeRepository->findIdsByParent($nodeId);
            foreach ($storeIds as $storeId) {
                if ($prepareChildIdsCallback) {
                    $preparedChildIds = $prepareChildIdsCallback($childIds, $storeId);
                } else {
                    $preparedChildIds = $childIds;
                }
                $hourSums = $childNodeReportRepository->calculateGrossSalesByIds($preparedChildIds, $storeId);
                foreach ($hourSums as $hourSum) {
                    $report = $nodeReportRepository->createByDayHourAndNodeId(
                        DateTimestamp::createFromMongoDate($hourSum['_id']),
                        (string) $nodeId,
                        (string) $storeId,
                        new Money($hourSum['hourSum'])
                    );
                    $dm->persist($report);
                    if (0 == ++$i % $batch) {
                        $dm->flush();
                        $dm->clear();
                    }
                }
                $dotHelper->write();
            }
        }

        $dm->flush();
        $dm->clear();

        $dotHelper->end();

        return $i;
    }
}
