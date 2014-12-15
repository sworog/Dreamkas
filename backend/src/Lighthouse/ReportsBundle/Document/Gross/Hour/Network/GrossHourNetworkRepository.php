<?php

namespace Lighthouse\ReportsBundle\Document\Gross\Hour\Network;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class GrossHourNetworkRepository extends DocumentRepository
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
     * @return ArrayIterator
     */
    public function recalculate()
    {
        $quantityPrecision = $this->numericFactory->createQuantity(0)->getPrecision();

        $ops = array(
            array(
                '$match' => array(
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
                    'hourDate' => '$createdDate.hourDate',
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$hourDate',
                    'grossSales' => array(
                        '$sum' => '$totalPrice'
                    ),
                    'costOfGoods' => array(
                        '$sum' => '$costOfGoods'
                    ),
                    'quantity' => array(
                        '$sum' => '$quantity.count'
                    ),
                    'grossMargin' => array(
                        '$sum' => array(
                            '$subtract' => array('$totalPrice', '$costOfGoods')
                        ),
                    )
                ),
            ),
            array(
                '$project' => array(
                    'grossSales' => true,
                    'costOfGoods' => true,
                    'grossMargin' => true,
                    'quantity' => array(
                        'count' => '$quantity',
                        'precision' => array('$literal' => $quantityPrecision),
                    ),
                    'hourDate' => '$_id'
                )
            ),
            array(
                '$out' => $this->getDocumentCollection()->getName()
            )
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }

    /**
     * @return Cursor|GrossHourNetwork[]
     */
    public function findAll()
    {
        return $this->findBy(array(), array('hourDate' => self::SORT_ASC));
    }
}
