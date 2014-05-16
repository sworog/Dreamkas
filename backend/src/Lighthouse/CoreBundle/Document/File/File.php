<?php

namespace Lighthouse\CoreBundle\Document\File;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoId;

/**
 * @property string $id
 * @property string $url
 * @property string $name
 * @property int $size
 *
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
        $this->id = (string) new MongoId();
    }
}
