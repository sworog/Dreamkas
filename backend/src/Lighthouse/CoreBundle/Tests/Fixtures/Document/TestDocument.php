<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Lighthouse\CoreBundle\Document\AbstractDocument;

class TestDocument extends AbstractDocument
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
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
        );
    }
}
