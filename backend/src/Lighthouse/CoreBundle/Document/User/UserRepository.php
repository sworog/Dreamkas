<?php

namespace Lighthouse\CoreBundle\Document\User;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class UserRepository extends DocumentRepository
{
    /**
     * @param string $role
     * @param array $excludeIds
     * @return \Doctrine\ODM\MongoDB\Cursor
     */
    public function findAllByRole($role, array $excludeIds = array())
    {
        $criteria = array('role' => $role);
        if (!empty($excludeIds)) {
            $criteria['id'] = array('$nin' => $excludeIds);
        }
        return $this->findBy($criteria);
    }
}
