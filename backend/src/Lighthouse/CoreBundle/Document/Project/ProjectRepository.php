<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class ProjectRepository extends DocumentRepository
{
    /**
     * @param string $name
     * @return Project|null
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function findByName($name)
    {
        return $this->find($name);
    }
}
