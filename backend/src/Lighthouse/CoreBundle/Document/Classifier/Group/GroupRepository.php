<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Group;

use Lighthouse\CoreBundle\Document\Classifier\ClassifierRepository;

class GroupRepository extends ClassifierRepository
{
    /**
     * @return mixed
     */
    protected function getParentFieldName()
    {
        return '';
    }

    /**
     * @param string $name
     * @param string $parentId
     * @return Group
     */
    public function findOneByName($name, $parentId = null)
    {
        return $this->findOneBy(array('name' => $name));
    }
}
