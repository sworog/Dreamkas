<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\UserProvider;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\UserRepository;

class AuthControllerTest extends WebTestCase
{
    public function testAuth()
    {
        $user = $this->createUser();
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        /* @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('lighthouse.core.document.repository.user');
        /* @var UserProvider $userProvider */
        $userProvider = $this->getContainer()->get('lighthouse.core.user.provider');

        $user = new User();
        $user->name = 'Админ Админыч';
        $user->username = 'admin';
        $user->role = 'administrator';
        $user->position = 'Администратор';

        $userProvider->setPassword($user, 'qwerty123');

        $userRepository->getDocumentManager()->persist($user);
        $userRepository->getDocumentManager()->flush();

        return $user;
    }
}
