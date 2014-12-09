<?php

namespace Lighthouse\ReportsBundle\Document\GrossReturn\Network;

use Doctrine\MongoDB\ArrayIterator;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GrossReturnNetworkRepository extends DocumentRepository
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
     * @param GrossReturnNetwork $dayReport
     * @return string
     */
    protected function getReportId(GrossReturnNetwork $dayReport)
    {
        return (string) $dayReport->day->getTimestamp();
    }

    /**
     * @param GrossReturnNetwork $report
     * @param array $result
     * @return GrossReturnNetwork
     */
    protected function setReportValues(GrossReturnNetwork $report, array $result)
    {
        $report->day = DateTimestamp::createFromParts(
            $result['_id']['year'],
            $result['_id']['month'],
            $result['_id']['day']
        );
        $report->quantity = $this->numericFactory->createQuantityFromCount($result['quantitySum']);
        $report->grossReturn = $this->numericFactory->createMoneyFromCount($result['grossReturn']);

        $report->id = $this->getReportId($report);

        return $report;
    }

    /**
     * @param OutputInterface $output
     * @param int $batch
     * @return int
     */
    public function recalculate(OutputInterface $output = null, $batch = 5000)
    {
        $this->dm->clear();

        $output = $output ?: new NullOutput();
        $dotHelper = new DotHelper($output);

        $requireDatePeriod = new DatePeriod("-1 year 00:00", "+1 day 23:59:59");

        $results = $this->aggregateByDays($requireDatePeriod->getStartDate(), $requireDatePeriod->getEndDate());
        $count = 0;

        $dotHelper->setTotalPositions(count($results));

        foreach ($results as $result) {
            $report = new GrossReturnNetwork();
            $this->setReportValues($report, $result);

            $this->dm->persist($report);

            if (++$count % $batch == 0) {
                $dotHelper->writeQuestion();
                $this->dm->flush();
                $this->dm->clear();
            } else {
                $dotHelper->write();
            }
        }

        $this->dm->flush();
        $this->dm->clear();

        $dotHelper->end();

        return $count;
    }

    /**
     * @param DateTimestamp $dateFrom
     * @param DateTimestamp $dateTo
     * @return ArrayIterator
     */
    protected function aggregateByDays(DateTimestamp $dateFrom, DateTimestamp $dateTo)
    {
        $ops = array(
            array(
                '$match' => array(
                    'createdDate.date' => array(
                        '$gte' => $dateFrom->getMongoDate(),
                        '$lt' => $dateTo->getMongoDate(),
                    ),
                    'reason.$ref' => ReturnProduct::TYPE,
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate.date' => self::SORT_ASC,
                )
            ),
            array(
                '$project' => array(
                    'totalPrice' => true,
                    'quantity' => true,
                    'year' => '$createdDate.year',
                    'month' => '$createdDate.month',
                    'day' => '$createdDate.day'
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'year' => '$year',
                        'month' => '$month',
                        'day' => '$day',
                    ),
                    'grossReturn' => array(
                        '$sum' => '$totalPrice'
                    ),
                    'quantitySum' => array(
                        '$sum' => '$quantity.count'
                    ),
                ),
            ),
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }
}
