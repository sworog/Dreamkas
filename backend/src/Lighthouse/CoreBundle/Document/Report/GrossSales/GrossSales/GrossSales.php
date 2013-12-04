<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales\DayGrossSales;
use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesReport;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\Serializer\Annotation as Serializer;

class GrossSales extends AbstractDocument
{
    /**
     * @var DayGrossSales
     */
    protected $yesterday;

    /**
     * @var DayGrossSales
     */
    protected $twoDaysAgo;

    /**
     * @var DayGrossSales
     */
    protected $eightDaysAgo;
}
