<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @property integer|float $fieldMin
 * @property integer|float $fieldMax
 * @property \DateTime $orderDate
 * @property \DateTime $createdDate
 *
 */
class CompareObjectFixture extends AbstractDocument
{
    /**
     * @var integer|float
     */
    protected $fieldMin;

    /**
     * @var integer|float
     */
    protected $fieldMax;

    /**
     * @var \DateTime
     */
    protected $orderDate;

    /**
     * @var \DateTime
     */
    protected $createdDate;
}
