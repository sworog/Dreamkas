<?php

namespace Lighthouse\ReportsBundle\Document\GrossMargin\Store;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class StoreDayGrossMarginRepository extends DocumentRepository
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
     * @param string $storeId
     * @param DateTime $day
     * @return string
     */
    public function getIdByStoreIdAndDay($storeId, DateTime $day)
    {
        return md5($storeId . ":" . $day->getTimestamp());
    }

    /**
     * @return ArrayIterator
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
                    'store' => 1,
                    'createdDate.date' => 1,
                )
            ),
            array(
                '$project' => array(
                    'store' => 1,
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
                        'store' => '$store',
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
     * @param string $storeId
     * @param DateTime $day
     * @param Money $sum
     * @return StoreDayGrossMargin
     */
    public function createByStoreId($storeId, DateTime $day, Money $sum = null)
    {
        return $this->createByStore(
            $this->dm->getReference(Store::getClassName(), $storeId),
            $day,
            $sum
        );
    }

    /**
     * @param Store $store
     * @param DateTime $day
     * @param Money $sum
     * @return StoreDayGrossMargin
     */
    public function createByStore(Store $store, DateTime $day, Money $sum = null)
    {
        $report = new StoreDayGrossMargin();
        $report->id = $this->getIdByStoreIdAndDay($this->getDocumentIdentifierValue($store), $day);
        $report->store = $store;
        $report->date = $day;
        $report->sum = ($sum) ? $sum : $this->numericFactory->createMoney(0);

        return $report;
    }

    /**
     * @return int
     */
    public function recalculate()
    {
        $results = $this->aggregateByDay();
        $count = 0;
        foreach ($results as $result) {
            $report = $this->createByStoreId(
                (string) $result['_id']['store'],
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
     * @param string $storeId
     * @param DateTime $date
     * @return Cursor|StoreDayGrossMargin[]
     */
    public function findByStoreId($storeId, DateTime $date)
    {
        $criteria = array(
            'store' => $storeId,
            'date.date' => array('$lt' => $date),
        );
        $sort = array(
            'date.date' => self::SORT_DESC
        );
        return $this->findBy($criteria, $sort);
    }
}
