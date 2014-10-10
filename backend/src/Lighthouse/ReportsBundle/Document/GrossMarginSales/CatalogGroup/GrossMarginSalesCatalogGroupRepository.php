<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use DateTime;

class GrossMarginSalesCatalogGroupRepository extends DocumentRepository
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function setTrialBalanceRepository(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @return Cursor|GrossMarginSalesCatalogGroup[]
     */
    public function findByPeriod(DateTime $dateFrom, DateTime $dateTo)
    {
        $dateFrom = new DateTimestamp($dateFrom);
        $dateTo = new DateTimestamp($dateTo);

        return $this->findBy(
            array(
                'day' => array(
                    '$gte' => $dateFrom->getMongoDate(),
                    '$lte' => $dateTo->getMongoDate(),
                ),
            )
        );
    }

    public function recalculate()
    {

    }
}
