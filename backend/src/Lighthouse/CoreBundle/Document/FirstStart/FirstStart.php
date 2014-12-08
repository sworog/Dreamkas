<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property string $id
 * @property bool   $complete
 * @property FirstStartStore[] $stores
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\FirstStart\FirstStartRepository")
 */
class FirstStart extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $complete = false;

    /**
     * @var FirstStartStore[]
     */
    protected $stores;
}
