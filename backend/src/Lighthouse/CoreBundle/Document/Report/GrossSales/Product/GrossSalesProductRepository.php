<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Product;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class GrossSalesProductRepository extends DocumentRepository
{
    /**
     * @param string $storeProductId
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByStoreProductIdAndDayHour($storeProductId, $dayHour)
    {
        return md5($storeProductId . ":" . $dayHour->getTimestamp());
    }


    public function findByStoreProductAndDayHour($storeProductId, $dayHour)
    {
        if (!$dayHour instanceof DateTime) {
            $dayHour = new DateTime($dayHour);
        }

        $reportId = $this->getIdByStoreProductIdAndDayHour($storeProductId, $dayHour);

        $report = $this->find($reportId);

        if (null === $report) {
            $report = new GrossSalesProductReport();
            $report->id = $reportId;
            $report->dayHour = $dayHour;
            $report->hourSum = new Money(0);
            $report->runningSum = new Money(0);
        }

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param StoreProduct $storeProduct
     * @param Money $runningSum
     * @param Money $hourSum
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProduct(
        DateTime $dayHour,
        StoreProduct $storeProduct,
        Money $runningSum = null,
        Money $hourSum = null
    ) {
        $report = new GrossSalesProductReport();
        $report->id = $this->getIdByStoreProductIdAndDayHour($storeProduct, $dayHour);
        $report->dayHour = $dayHour;
        $report->product = $storeProduct;
        $report->runningSum = $runningSum ?: new Money(0);
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $storeProductId
     * @param Money $runningSum
     * @param Money $hourSum
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProductId(
        DateTime $dayHour,
        $storeProductId,
        Money $runningSum = null,
        Money $hourSum = null
    ) {
        $storeProduct = $this->dm->getReference(StoreProduct::getClassName(), $storeProductId);
        return $this->createByDayHourAndStoreProduct($dayHour, $storeProduct, $runningSum, $hourSum);
    }
}
