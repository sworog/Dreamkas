<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\Serializer\Annotation as Serializer;

class Test extends AbstractDocument
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var \DateTime
     */
    protected $orderDate;

    /**
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @var Money
     */
    protected $money;
}
