<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales;

use Doctrine\MongoDB\ArrayIterator;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GrossMarginSalesRepository extends DocumentRepository
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
     * @param GrossMarginSales $report
     * @param array $result
     * @return GrossMarginSales
     */
    protected function setReportValues(GrossMarginSales $report, array $result)
    {
        $report->day = DateTimestamp::createFromParts(
            $result['_id']['year'],
            $result['_id']['month'],
            $result['_id']['day']
        );
        $report->costOfGoods = $this->numericFactory->createMoneyFromCount($result['costOfGoodsSum']);
        $report->quantity = $this->numericFactory->createQuantityFromCount($result['quantitySum']);
        $report->grossSales = $this->numericFactory->createMoneyFromCount($result['grossSales']);
        $report->grossMargin = $this->numericFactory->createMoneyFromCount($result['grossMargin']);

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
            $report = $this->createReport($result);
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
     * @param GrossMarginSales $dayReport
     * @return string
     */
    protected function getReportId(GrossMarginSales $dayReport)
    {
        $id = (string) $dayReport->day->getTimestamp();
        if ($dayReport->store) {
            $id.= ':' . $this->getDocumentIdentifierValue($dayReport->store);
        }
        if ($dayReport->getItem()) {
            $id.= ':' . $this->getDocumentIdentifierValue($dayReport->getItem());
        }
        return $id;
    }

    /**
     * @param DateTimestamp $dateFrom
     * @param DateTimestamp $dateTo
     * @return ArrayIterator
     */
    abstract protected function aggregateByDays(DateTimestamp $dateFrom, DateTimestamp $dateTo);

    /**
     * @param array $result
     * @return GrossMarginSales
     */
    abstract protected function createReport(array $result);
}
