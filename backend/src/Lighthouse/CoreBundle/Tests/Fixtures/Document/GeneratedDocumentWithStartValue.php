<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;

/**
 * @MongoDB\Document
 */
class GeneratedDocumentWithStartValue
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $sku;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return int
     */
    public function getSku()
    {
        return $this->sku;
    }
}
