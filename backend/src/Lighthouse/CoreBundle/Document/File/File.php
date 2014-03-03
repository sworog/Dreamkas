<?php

namespace Lighthouse\CoreBundle\Document\File;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoId;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\File\FileRepository"
 * )
 */
class File extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $url;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\Int
     * @var int
     */
    protected $size;

    public function __construct()
    {
        $this->id = new MongoId();
    }
}
