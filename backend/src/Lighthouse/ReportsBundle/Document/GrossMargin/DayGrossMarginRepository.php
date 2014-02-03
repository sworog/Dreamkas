<?php

namespace Lighthouse\ReportsBundle\Document\GrossMargin;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class DayGrossMarginRepository extends DocumentRepository
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
     * @param DateTime $date
     * @return null|DayGrossMargin
     */
    public function findOneByDate(\DateTime $date)
    {
        return $this->findOneBy(array('date.date' => $date));
    }

    /**
     * @return int
     */
    public function recalculate()
    {
        $results = $this->aggregateByDay();
        $count = 0;
        foreach ($results as $result) {
            $report = $this->createByDay(
                $this->createDay($result['_id']['year'], $result['_id']['month'], $result['_id']['day']),
                $this->numericFactory->createMoneyFromCount($result['sum'])
            );
            $this->dm->persist($report);
            $count++;
        }
        $this->dm->flush();
        return $count;
    }

    /**
     * @return array
     */
    public function aggregateByDay()
    {
        $ops = array(
            array(
                '$match' => array(
                    'reason.$ref' => SaleProduct::REASON_TYPE,
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate.date' => 1,
                )
            ),
            array(
                '$project' => array(
                    'totalPrice' => 1,
                    'costOfGoods' => 1,
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
                    'totalPriceSum' => array(
                        '$sum' => '$totalPrice'
                    ),
                    'costOfGoodsSum' => array(
                        '$sum' => '$costOfGoods'
                    ),
                ),
            ),
            array(
                '$project' => array(
                    '_id' => 1,
                    'totalPriceSum' => 1,
                    'costOfGoodsSum' => 1,
                    'sum' => array(
                        '$subtract' => array('$totalPriceSum', '$costOfGoodsSum')
                    )
                )
            )
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return DateTimestamp
     */
    protected function createDay($year, $month, $day)
    {
        return DateTimestamp::createFromParts($year, $month, $day);
    }

    /**
     * @param DateTimestamp $day
     * @param Money $sum
     * @return DayGrossMargin
     */
    protected function createByDay(DateTimestamp $day, Money $sum)
    {
        $dayGrossMargin = new DayGrossMargin();
        $dayGrossMargin->date = $day;
        $dayGrossMargin->sum = $sum;

        return $dayGrossMargin;
    }
}
