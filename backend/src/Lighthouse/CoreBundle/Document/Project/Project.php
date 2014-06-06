<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 * @property string $id
 * @property string $name
 *
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
     * @MongoDB\String
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name) {
            return $this->name;
        } else {
            return $this->id;
        }
    }
}
