<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMargin;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\ReportsBundle\Document\GrossMargin\DayGrossMarginRepository;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMargin;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginCollection;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginRepository;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\DayGrossMarginCollection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.reports.gross_margin.manager")
 */
class GrossMarginManager
{
    /**
     * @var StoreDayGrossMarginRepository
     */
    protected $storeDayGrossMarginRepository;

    /**
     * @var CostOfGoodsCalculator
     */
    protected $costOfGoodCalculator;

    /**
     * @var DayGrossMarginRepository
     */
    protected $dayGrossMarginRepository;

    /**
     * @DI\InjectParams({
     *     "storeDayGrossMarginRepository" = @DI\Inject(
     *          "lighthouse.reports.document.gross_margin.store.repository"
     *      ),
     *      "costOfGoodCalculator" = @DI\Inject(
     *          "lighthouse.core.document.trial_balance.calculator"
     *      ),
     *      "dayGrossMarginRepository" = @DI\Inject(
     *          "lighthouse.reports.document.gross_margin.repository"
     *      ),
     * })
     *
     * @param StoreDayGrossMarginRepository $storeDayGrossMarginRepository
     * @param CostOfGoodsCalculator $costOfGoodCalculator
     * @param DayGrossMarginRepository $dayGrossMarginRepository
     */
    public function __construct(
        StoreDayGrossMarginRepository $storeDayGrossMarginRepository,
        CostOfGoodsCalculator $costOfGoodCalculator,
        DayGrossMarginRepository $dayGrossMarginRepository
    ) {
        $this->storeDayGrossMarginRepository = $storeDayGrossMarginRepository;
        $this->costOfGoodCalculator = $costOfGoodCalculator;
        $this->dayGrossMarginRepository = $dayGrossMarginRepository;
    }

    /**
     * @param Store $store
     * @param DateTime $date
     * @return StoreDayGrossMarginCollection
     */
    public function getStoreGrossMarginReport(Store $store, DateTime $date)
    {
        $date->setTime(0, 0, 0);
        $cursor = $this->storeDayGrossMarginRepository->findByStoreId($store->id, $date);
        $collection = $this->fillStoreDayGrossMarginCollection($cursor, $date);
        return $collection;
    }

    /**
     * @param StoreDayGrossMargin[] $cursor
     * @param DateTime $date
     * @return DayGrossMarginCollection
     */
    protected function fillStoreDayGrossMarginCollection(array $cursor, DateTime $date)
    {
        $collection = new DayGrossMarginCollection();
        $previousDay = $date;
        foreach ($cursor as $storeDayGrossMargin) {
            $missingDays = $this->getMissingStoreGrossMarginDays(
                $storeDayGrossMargin->store,
                $storeDayGrossMargin->date,
                $previousDay
            );
            $collection->append($missingDays);
            $collection->add($storeDayGrossMargin);
            $previousDay = $storeDayGrossMargin->date;
        }
        return $collection;
    }

    /**
     * @param Store $store
     * @param DateTime $currentDay
     * @param DateTime $previousDay
     * @return StoreDayGrossMargin[]
     */
    protected function getMissingStoreGrossMarginDays(
        Store $store,
        DateTime $currentDay,
        DateTime $previousDay = null
    ) {
        $missingDays = array();
        if ($previousDay) {
            $day = clone $previousDay;
            while ($day->modify('-1 day') > $currentDay) {
                $missingDays[] = $this->storeDayGrossMarginRepository->createByStore($store, $day);
                $day = clone $day;
            }
        }
        return $missingDays;
    }

    /**
     * @param DateTime $date
     * @return DayGrossMarginCollection
     */
    public function getGrossMarginReport(DateTime $date)
    {
        $date->setTime(0, 0, 0);
        $cursor = $this->dayGrossMarginRepository->findByDate($date);
        $collection = $this->fillDayGrossMarginCollection($cursor, $date);
        return $collection;
    }

    /**
     * @param StoreDayGrossMargin[] $cursor
     * @param DateTime $date
     * @return DayGrossMarginCollection
     */
    protected function fillDayGrossMarginCollection(array $cursor, DateTime $date)
    {
        $collection = new DayGrossMarginCollection();
        $previousDay = $date;
        foreach ($cursor as $storeDayGrossMargin) {
            $missingDays = $this->getMissingGrossMarginDays(
                $storeDayGrossMargin->date,
                $previousDay
            );
            $collection->append($missingDays);
            $collection->add($storeDayGrossMargin);
            $previousDay = $storeDayGrossMargin->date;
        }
        return $collection;
    }

    /**
     * @param DateTime $currentDay
     * @param DateTime $previousDay
     * @return StoreDayGrossMargin[]
     */
    protected function getMissingGrossMarginDays(
        DateTime $currentDay,
        DateTime $previousDay = null
    ) {
        $missingDays = array();
        if ($previousDay) {
            $day = clone $previousDay;
            while ($day->modify('-1 day') > $currentDay) {
                $missingDays[] = $this->dayGrossMarginRepository->createByDay($day);
                $day = clone $day;
            }
        }
        return $missingDays;
    }

    /**
     * @return int
     */
    public function recalculateStoreGrossMargin()
    {
        return $this->storeDayGrossMarginRepository->recalculate();
    }

    /**
     * @param OutputInterface|null $output
     */
    public function calculateGrossMarginUnprocessedTrialBalance(OutputInterface $output = null)
    {
        $this->costOfGoodCalculator->calculateUnprocessed($output);
    }

    public function recalculateDayGrossMargin()
    {
        return $this->dayGrossMarginRepository->recalculate();
    }
}
