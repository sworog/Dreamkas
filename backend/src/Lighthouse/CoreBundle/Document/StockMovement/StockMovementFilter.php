<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use DateTime;

/**
 * @property DateTime $dateFrom
 * @property DateTime $dateTo
 * @property array $types
 */
class StockMovementFilter extends AbstractDocument implements FilterInterface
{
    /**
     * @var DateTime
     */
    protected $dateFrom;

    /**
     * @var DateTime
     */
    protected $dateTo;

    /**
     * @var array
     */
    protected $types;

    /**
     * @param string $types
     */
    public function setTypes($types)
    {
        $types = explode(',', $types);
        $types = array_map('trim', $types);
        $this->types = $types;
    }

    /**
     * @param DateTime $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = new DateTimestamp($dateTo);
    }

    /**
     * @param DateTime $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = new DateTimestamp($dateFrom);
    }
}
