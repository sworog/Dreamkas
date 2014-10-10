<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use DateTime;
use Lighthouse\CoreBundle\Util\Iterator\ArrayIterator;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param string $subCategoryId
     * @param DateTime $day
     * @return string
     */
    public function getIdByStoreProductIdAndDay($subCategoryId, $day)
    {
        return md5($subCategoryId . ":" . $day->getTimestamp());
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @param string $storeId
     * @return Cursor|GrossMarginSalesCatalogGroup[]
     */
    public function findByPeriod(DateTime $dateFrom, DateTime $dateTo, $storeId = null)
    {
        $dateFrom = new DateTimestamp($dateFrom);
        $dateTo = new DateTimestamp($dateTo);

        $criteria = array(
            'day' => array(
                '$gte' => $dateFrom->getMongoDate(),
                '$lte' => $dateTo->getMongoDate(),
            )
        );

        if ($storeId) {
            $criteria['store'] = new \MongoId($storeId);
        }

        return $this->findBy($criteria);
    }

    /**
     * @param OutputInterface $output
     * @param int $batch
     * @return int
     */
    public function recalculate(OutputInterface $output = null, $batch = 5000)
    {
        if (null == $output) {
            $output = new NullOutput();
        }
        $dotHelper = new DotHelper($output);

        $requireDatePeriod = new DatePeriod("-8 day 00:00", "+1 day 23:59:59");

        $results = $this->aggregateByDays($requireDatePeriod->getStartDate(), $requireDatePeriod->getEndDate());
        $count = 0;

        $dotHelper->setTotalPositions(count($results));

        foreach ($results as $result) {
            $report = new GrossMarginSalesCatalogGroup();
            $report->day = DateTimestamp::createFromParts(
                $result['_id']['year'],
                $result['_id']['month'],
                $result['_id']['day']
            );
            $report->id = $this->getIdByStoreProductIdAndDay($result['_id']['subCategory'], $report->day);
            $report->costOfGoods = $this->numericFactory->createMoneyFromCount($result['costOfGoodsSum']);
            $report->quantity = $this->numericFactory->createQuantityFromCount($result['quantitySum']);
            $report->grossSales = $this->numericFactory->createMoneyFromCount($result['grossSales']);
            $report->grossMargin = $this->numericFactory->createMoneyFromCount($result['grossMargin']);
            $report->store = $this->dm->getReference(
                Store::getClassName(),
                $result['_id']['store']
            );
            $report->subCategory = $this->dm->getReference(
                SubCategory::getClassName(),
                $result['_id']['subCategory']
            );

            $this->dm->persist($report);
            $count++;
            $dotHelper->write();

            if ($count % $batch == 0) {
                $this->dm->flush();
            }
        }

        $this->dm->flush();

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
                    'reason.$ref' => SaleProduct::TYPE,
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
                    'costOfGoods' => true,
                    'quantity' => true,
                    'subCategory' => true,
                    'store' => true,
                    'year' => '$createdDate.year',
                    'month' => '$createdDate.month',
                    'day' => '$createdDate.day'
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'subCategory' => '$subCategory',
                        'store' => '$store',
                        'year' => '$year',
                        'month' => '$month',
                        'day' => '$day',
                    ),
                    'grossSales' => array(
                        '$sum' => '$totalPrice'
                    ),
                    'costOfGoodsSum' => array(
                        '$sum' => '$costOfGoods'
                    ),
                    'quantitySum' => array(
                        '$sum' => '$quantity.count'
                    )
                ),
            ),
            array(
                '$project' => array(
                    '_id' => 1,
                    'grossSales' => 1,
                    'costOfGoodsSum' => 1,
                    'quantitySum' => 1,
                    'grossMargin' => array(
                        '$subtract' => array('$grossSales', '$costOfGoodsSum')
                    )
                )
            )
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }
}
