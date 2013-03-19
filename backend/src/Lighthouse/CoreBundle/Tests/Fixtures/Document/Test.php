<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Lighthouse\CoreBundle\Document\AbstractDocument;
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
     * @var string
     */
    protected $orderDate;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'orderDate' => $this->orderDate,
        );
    }
}
