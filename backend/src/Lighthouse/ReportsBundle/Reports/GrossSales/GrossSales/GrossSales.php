<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;

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
