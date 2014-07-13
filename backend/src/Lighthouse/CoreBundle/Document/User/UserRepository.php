<?php

namespace Lighthouse\CoreBundle\Document\User;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class UserRepository extends DocumentRepository
{
    /**
     * @param string $role
     * @param array $excludeIds
     * @return Cursor|User[]
     */
    public function findAllByRoles($role, array $excludeIds = array())
    {
        $criteria = array('roles' => $role);
        if (!empty($excludeIds)) {
            $criteria['id'] = array('$nin' => $excludeIds);
        }
        return $this->findBy($criteria);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findOneByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }
}
