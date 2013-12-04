<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Group;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class GroupRepository extends DocumentRepository
{

    /**
     * @param string $name
     * @return Group
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }
}
