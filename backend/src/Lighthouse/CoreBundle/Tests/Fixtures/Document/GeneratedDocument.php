<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 * @MongoDB\Document
 * @GlobalDb
 */
class GeneratedDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @Generated
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
