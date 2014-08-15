<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property DateTime $dateFrom
 * @property DateTime $dateTo
 * @property array $types
 */
class StockMovementFilter extends AbstractDocument
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

    /**
     * @var array
     */
    protected $types = array();

    /**
     * @return bool
     */
    public function issetTypes()
    {
        return is_array($this->types) && !empty($this->types);
    }
}
