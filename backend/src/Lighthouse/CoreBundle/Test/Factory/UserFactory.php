<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Security\Project\ProjectToken;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserFactory extends AbstractFactory
{
    const USER_DEFAULT_EMAIL = 'default@lighthouse.pro';
    const USER_DEFAULT_PASSWORD = 'password';
    const USER_DEFAULT_NAME = 'Админ Админыч';
    const USER_DEFAULT_POSITION = 'Администратор';
    const PROJECT_DEFAULT_NAME = 'project1';

    /**
     * @var array(email => id)
     */
    protected $users = array();

    /**
     * @var array
     */
    protected $projectIds = array();

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
     * @param string|array $roles
     * @param string $name
     * @param string $position
     *
     * @return User
     */
    public function getUser(
        $email = self::USER_DEFAULT_EMAIL,
        $password = self::USER_DEFAULT_PASSWORD,
        $roles = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION
    ) {
        if (!isset($this->users[$email])) {
            $this->createUser($email, $password, $roles, $name, $position);
        }
        return $this->getUserRepository()->findOneByEmail($email);
    }

    /**
     * @param string $userId
     * @return User
     */
    public function getUserById($userId)
    {
        return $this->getUserRepository()->find($userId);
    }

    /**
     * @param string $email
     * @param string $password
     * @param array|string $roles
     * @param string $name
     * @param string $position
     * @param \Lighthouse\CoreBundle\Document\Project\Project $project
     * @return User
     */
    public function createUser(
        $email = self::USER_DEFAULT_EMAIL,
        $password = self::USER_DEFAULT_PASSWORD,
        $roles = User::ROLE_ADMINISTRATOR,
        $name = self::USER_DEFAULT_NAME,
        $position = self::USER_DEFAULT_POSITION,
        Project $project = null
    ) {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->roles = (array) $roles;
        $user->position = $position;

        $user->project = ($project) ?: $this->getProject();

        $this->getUserProvider()->setPassword($user, $password);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        $this->users[$email] = $user->id;

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $projectName
     * @return User
     */
    public function createProjectUser(
        $email = self::USER_DEFAULT_EMAIL,
        $password = self::USER_DEFAULT_PASSWORD,
        $projectName = self::PROJECT_DEFAULT_NAME
    ) {
        $project = $this->getProject($projectName);
        return $this->createUser(
            $email,
            $password,
            User::getDefaultRoles(),
            null,
            null,
            $project
        );
    }

    /**
     * @param string $email
     * @return User
     */
    public function getProjectUser($email = self::USER_DEFAULT_EMAIL)
    {
        if (!isset($this->users[$email])) {
            $this->createProjectUser($email);
        }
        return $this->getUserRepository()->findOneByEmail($email);
    }

    /**
     * @param string $name
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @return Project
     */
    public function getProject($name = self::PROJECT_DEFAULT_NAME)
    {
        if (!isset($this->projectIds[$name])) {
            $this->projectIds[$name] = $this->createProject($name)->id;
        }
        return $this->getProjectRepository()->find($this->projectIds[$name]);
    }

    /**
     * @param string $name
     * @return Project
     */
    public function createProject($name = self::PROJECT_DEFAULT_NAME)
    {
        $project = new Project();
        $project->name = $name;
        $this->getProjectRepository()->save($project);
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
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.user');
    }

    /**
     * @return ProjectRepository
     */
    protected function getProjectRepository()
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
