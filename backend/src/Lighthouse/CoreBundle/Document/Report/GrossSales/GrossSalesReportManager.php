<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores\GrossSalesByStoresCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByStores\StoreGrossSalesByStores;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReport;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use DateInterval;

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
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @DI\InjectParams({
     *      "grossSalesRepository" = @DI\Inject("lighthouse.core.document.repository.store_gross_sales"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     * })
     * @param StoreGrossSalesRepository $grossSalesRepository
     * @param StoreRepository $storeRepository
     */
    public function __construct(StoreGrossSalesRepository $grossSalesRepository, StoreRepository $storeRepository)
    {
        $this->grossSalesRepository = $grossSalesRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @param DateTime|string $time
     * @param array $intervals
     * @return DateTimestamp[]
     */
    public function getDates($time, array $intervals)
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
     * @param DateTime|string $time
     * @return GrossSalesByStoresCollection
     */
    public function getGrossSalesByStores($time)
    {
        $intervals = array(
            'yesterday' => '-1 day 23:00',
            'twoDaysAgo' => '-2 days 23:00',
            'eightDaysAgo' => '-8 days 23:00',
        );
        $dates = $this->getDates($time, $intervals);
        $storeDayReports = $this->grossSalesRepository->findByDates($dates);
        $storeDayReports->sort(array('store' => 1));
        /* @var Store[] $stores */
        $stores = $this->storeRepository->findAll()->sort(array('id' => 1));
        $storeReports = $this->createGrossSalesByStoresCollection($storeDayReports, $dates);
        $this->fillGrossSalesByStoresCollection($storeReports, $stores, $dates);
        return $storeReports;
    }

    /**
     * @param Cursor|StoreGrossSalesReport[] $storeDayReports
     * @param DateTime[] $dates
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
}
