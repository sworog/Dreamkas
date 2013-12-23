<?php

namespace Lighthouse\CoreBundle\Tests\Security\User;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class UserProviderTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserInvalidUser()
    {
        /* @var UserProvider $userProvider */
        $userProvider = $this->getContainer()->get('lighthouse.core.user.provider');

        $invalidUser = $this->getMock('Symfony\\Component\\Security\\Core\\User\\UserInterface');

        $userProvider->refreshUser($invalidUser);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage User with id unknown not found
     */
    public function testRefreshUserNotFound()
    {
        /* @var UserProvider $userProvider */
        $userProvider = $this->getContainer()->get('lighthouse.core.user.provider');

        $user = new User();
        $user->id = 'unknown';

        $userProvider->refreshUser($user);
    }

    public function testRefreshUser()
    {
        $this->clearMongoDb();

        /* @var UserProvider $userProvider */
        $userProvider = $this->getContainer()->get('lighthouse.core.user.provider');

        $user = $userProvider->createNewUser(
            'username',
            'password',
            'name',
            User::ROLE_ADMINISTRATOR,
            'position'
        );

        $this->getDocumentManager()->clear();

        $serializedUser = serialize($user);
        $unserializedUser = unserialize($serializedUser);

        $this->assertNotSame($user, $unserializedUser);

        $refreshedUser = $userProvider->refreshUser($unserializedUser);

        $this->assertEquals($user->id, $refreshedUser->id);
        $this->assertEquals($user->username, $refreshedUser->username);
        $this->assertNotSame($user, $refreshedUser);
    }
}
