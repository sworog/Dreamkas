<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\Project\ProjectToken;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserFactory extends AbstractFactory
{
    const USER_DEFAULT_EMAIL = 'default@lighthouse.pro';
    const USER_DEFAULT_PASSWORD = 'password';
    const USER_DEFAULT_NAME = 'Админ Админыч';
    const USER_DEFAULT_POSITION = 'Администратор';

    /**
     * @var User[]
     */
    protected $users = array();

    /**
     * @var string
     */
    protected $projectId;

    /**
     * @param string $role
     * @return User
     */
    public function getRoleUser($role)
    {
        return $this->getUser($role . '@lighthouse.pro', UserFactory::USER_DEFAULT_PASSWORD, $role, $role, $role);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     *
     * @return User
     */
    public function getUser(
        $email = self::USER_DEFAULT_EMAIL,
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION
    ) {
        $hash = md5(implode(',', func_get_args()));
        if (!isset($this->users[$hash])) {
            $this->users[$hash] = $this->createUser($email, $password, $role, $name, $position);
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
        return null;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     * @return User
     */
    public function createUser(
        $email = self::USER_DEFAULT_EMAIL,
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION
    ) {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->roles = array($role);
        $user->position = $position;

        $user->project = $this->getProject();

        $this->getUserProvider()->setPassword($user, $password);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $user;
    }

    /**
     * @return Project
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function getProject()
    {
        if (!$this->projectId) {
            $this->projectId = $this->createProject()->id;
        }
        return $this->getProjectProvider()->find($this->projectId);
    }

    /**
     * @return Project
     */
    public function createProject()
    {
        $project = new Project();
        $this->getProjectProvider()->save($project);
        return $project;
    }

    /**
     * @return ProjectToken
     */
    public function authProject()
    {
        $token = new ProjectToken($this->getProject());
        $this->getSecurityContext()->setToken($token);
        return $token;
    }

    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->container->get('lighthouse.core.user.provider');
    }

    /**
     * @return ProjectRepository
     */
    protected function getProjectProvider()
    {
        return $this->container->get('lighthouse.core.document.repository.project');
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->container->get('security.context');
    }
}
