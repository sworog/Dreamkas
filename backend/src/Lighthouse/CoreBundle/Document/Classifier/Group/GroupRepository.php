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
     * @return Group
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }
}
