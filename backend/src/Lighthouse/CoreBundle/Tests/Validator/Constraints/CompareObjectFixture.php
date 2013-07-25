<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;

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
