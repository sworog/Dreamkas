<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMargin;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Store\Store;
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
     * @DI\InjectParams({
     *     "storeDayGrossMarginRepository" = @DI\Inject(
     *          "lighthouse.reports.document.gross_margin.store.repository"
     *      )
     * })
     *
     * @param StoreDayGrossMarginRepository $storeDayGrossMarginRepository
     */
    public function __construct(StoreDayGrossMarginRepository $storeDayGrossMarginRepository)
    {
        $this->storeDayGrossMarginRepository = $storeDayGrossMarginRepository;
    }

    /**
     * @param Store $store
     * @param DateTime $date
     * @return \Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginCollection
     */
    public function getStoreGrossMarginReport(Store $store, DateTime $date)
    {
        $cursor = $this->storeDayGrossMarginRepository->findByStoreId($store->id);
        $collection = $this->fillStoreDayGrossMarginCollection($cursor);
        return $collection;
    }

    /**
     * @param Cursor|StoreDayGrossMargin[] $cursor
     * @return StoreDayGrossMarginCollection::
     */
    protected function fillStoreDayGrossMarginCollection(Cursor $cursor)
    {
        $collection = new StoreDayGrossMarginCollection();
        $previousDay = null;
        foreach ($cursor as $storeDayGrossMargin) {
            foreach ($this->getMissingStoreGrossMarginDays($storeDayGrossMargin, $previousDay) as $missingDay) {
                $collection->add($missingDay);
            }
            $collection->add($storeDayGrossMargin);
            $previousDay = $storeDayGrossMargin;
        }
        return $collection;
    }

    /**
     * @param StoreDayGrossMargin $currentDay
     * @param StoreDayGrossMargin $previousDay
     * @return StoreDayGrossMargin[]
     */
    protected function getMissingStoreGrossMarginDays(
        StoreDayGrossMargin $currentDay,
        StoreDayGrossMargin $previousDay = null
    ) {
        $missingDays = array();
        if ($previousDay) {
            $day = clone $previousDay->day;
            while ($day->modify('+1 day') < $currentDay) {
                $missingDays[] = $this->storeDayGrossMarginRepository->createByStore($currentDay->store, $day);
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
}
