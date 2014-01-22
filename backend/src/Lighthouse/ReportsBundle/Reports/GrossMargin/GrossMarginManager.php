<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMargin;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoodCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Tests\Document\TrialBalance\CostOfGoodsTest;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMargin;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginRepository;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginCollection;

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
     * @var CostOfGoodCalculator
     */
    protected $costOfGoodCalculator;

    /**
     * @DI\InjectParams({
     *     "storeDayGrossMarginRepository" = @DI\Inject(
     *          "lighthouse.reports.document.gross_margin.store.repository"
     *      ),
     *      "costOfGoodCalculator" = @DI\Inject(
     *          "lighthouse.core.document.trial_balance.calculator"
     *      ),
     * })
     *
     * @param StoreDayGrossMarginRepository $storeDayGrossMarginRepository
     * @param CostOfGoodCalculator $costOfGoodCalculator
     */
    public function __construct(
        StoreDayGrossMarginRepository $storeDayGrossMarginRepository,
        CostOfGoodCalculator $costOfGoodCalculator
    ) {
        $this->storeDayGrossMarginRepository = $storeDayGrossMarginRepository;
        $this->costOfGoodCalculator = $costOfGoodCalculator;
    }

    /**
     * @param Store $store
     * @param DateTime $date
     * @return \Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginCollection
     */
    public function getStoreGrossMarginReport(Store $store, DateTime $date)
    {
        $date->setTime(0, 0, 0);
        $cursor = $this->storeDayGrossMarginRepository->findByStoreId($store->id, $date);
        $collection = $this->fillStoreDayGrossMarginCollection($cursor, $date);
        return $collection;
    }

    /**
     * @param Cursor|StoreDayGrossMargin[] $cursor
     * @param DateTime $date
     * @return StoreDayGrossMarginCollection::
     */
    protected function fillStoreDayGrossMarginCollection(Cursor $cursor, DateTime $date)
    {
        $collection = new StoreDayGrossMarginCollection();
        $previousDay = null;
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
     * @return int
     */
    public function recalculateStoreGrossMargin()
    {
        return $this->storeDayGrossMarginRepository->recalculate();
    }

    /**
     * @param int $limit
     * @return int
     */
    public function calculateUnprocessedTrialBalances($limit = null)
    {
        return $this->costOfGoodCalculator->calculateUnprocessedTrialBalances($limit);
    }
}
