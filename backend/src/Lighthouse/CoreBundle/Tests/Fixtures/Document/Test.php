<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Types\Money;

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
