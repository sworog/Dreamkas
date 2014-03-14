<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;

class UserFactory extends AbstractFactoryFactory
{
    const USER_DEFAULT_USERNAME = 'admin';
    const USER_DEFAULT_PASSWORD = 'password';
    const USER_DEFAULT_NAME = 'Админ Админыч';
    const USER_DEFAULT_POSITION = 'Администратор';

    /**
     * @var User[]
     */
    protected $users = array();

    /**
     * @param string $role
     * @return User
     */
    public function getRoleUser($role)
    {
        return $this->getUser($role, UserFactory::USER_DEFAULT_PASSWORD, $role, $role, $role);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     *
     * @return User
     */
    public function getUser(
        $username = self::USER_DEFAULT_USERNAME,
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION
    ) {
        $hash = md5(implode(',', func_get_args()));
        if (!isset($this->users[$hash])) {
            $this->users[$hash] = $this->createUser($username, $password, $role, $name, $position);
        }
        return $this->users[$hash];
    }

    /**
     * @param string $userId
     * @return User
     */
    public function getUserById($userId)
    {
        foreach ($this->users as $user) {
            if ($user->id == $userId) {
                return $user;
            }
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     * @return User
     */
    public function createUser(
        $username = self::USER_DEFAULT_USERNAME,
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION
    ) {
        $user = new User();
        $user->name = $name;
        $user->username = $username;
        $user->role = $role;
        $user->position = $position;

        $this->getUserProvider()->setPassword($user, $password);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $user;
    }

    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->container->get('lighthouse.core.user.provider');
    }
}
