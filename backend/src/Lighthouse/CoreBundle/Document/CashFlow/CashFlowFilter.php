<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @property DateTime $dateFrom
 * @property DateTime $dateTo
 *
 */
class CashFlowFilter extends AbstractDocument
{
    /**
     * @Assert\DateTime
     * @var DateTime
     */
    protected $dateFrom;

    /**
     * @Assert\DateTime
     * @var DateTime
     */
    protected $dateTo;
}
