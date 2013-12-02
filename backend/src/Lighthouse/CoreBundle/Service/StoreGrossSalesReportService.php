<?php

namespace Lighthouse\CoreBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

/**
 * @DI\Service("lighthouse.core.service.store.report.gross_sales")
 */
class StoreGrossSalesReportService
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var StoreGrossSalesRepository
     */
    protected $storeGrossSalesRepository;

    /**
     * @DI\InjectParams({
     *     "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *     "storeGrossSalesRepository" = @DI\Inject("lighthouse.core.document.repository.store_gross_sales")
     * })
     *
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param StoreGrossSalesRepository $storeGrossSalesRepository
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        StoreGrossSalesRepository $storeGrossSalesRepository
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->storeGrossSalesRepository = $storeGrossSalesRepository;
    }

    /**
     * @return int
     */
    public function recalculateStoreGrossSalesReport()
    {
        $results = $this->trialBalanceRepository->calculateGrossSales();
        foreach ($results as $result) {
            $storeId = $result['_id']['store'];
            $day = DateTimestamp::createFromMongoDate($result['_id']['day']);
            foreach ($result['value'] as $hour => $grossSales) {
                $day->setTime($hour, 0);
                $this->storeGrossSalesRepository->updateStoreDayHourGrossSales(
                    $storeId,
                    $day,
                    $grossSales['runningSum'],
                    $grossSales['hourSum']
                );
            }
        }

        return count($results);
    }
}
