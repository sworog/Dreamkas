<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSales;

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
