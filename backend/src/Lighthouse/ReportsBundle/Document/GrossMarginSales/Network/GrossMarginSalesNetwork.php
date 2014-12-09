<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Network;

use DateTime;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlowable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Network\GrossMarginSalesNetworkRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc"})
 */
class GrossMarginSalesNetwork extends GrossMarginSales implements CashFlowable
{
    /**
     * @return object
     */
    public function getItem()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function cashFlowNeeded()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCashFlowReasonType()
    {
        return 'Sales';
    }

    /**
     * @return Money
     */
    public function getCashFlowAmount()
    {
        return $this->grossSales;
    }

    /**
     * @return string
     */
    public function getCashFlowDirection()
    {
        return CashFlow::DIRECTION_IN;
    }

    /**
     * @return DateTime;
     */
    public function getCashFlowDate()
    {
        $date = clone $this->day;
        return $date->setTime(23, 59, 59);
    }

    /**
     * @return DateTime
     */
    public function getCashFlowReasonDate()
    {
        return $this->day;
    }
}
