<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Project\ProjectRepository"
 * )
 * @GlobalDb
 */
class Project extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->id;
    }
}
