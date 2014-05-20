<?php

namespace Lighthouse\CoreBundle\Tests\Fixtures\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Tests\Fixtures\Document\TestDocumentRepository")
 */
class TestDocument extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;
}
