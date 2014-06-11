<?php

namespace Lighthouse\CoreBundle\Tests\Security\User;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends ContainerAwareTestCase
{
    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->getContainer()->get('lighthouse.core.user.provider');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserInvalidUser()
    {
        $this->clearMongoDb();

        /* @var UserInterface $invalidUser */
        $invalidUser = $this->getMock('Symfony\\Component\\Security\\Core\\User\\UserInterface');

        $this->getUserProvider()->refreshUser($invalidUser);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage User with id unknown not found
     */
    public function testRefreshUserNotFound()
    {
        $this->clearMongoDb();

        $user = new User();
        $user->id = 'unknown';

        $this->getUserProvider()->refreshUser($user);
    }

    public function testRefreshUser()
    {
        $this->clearMongoDb();

        $userProvider = $this->getUserProvider();

        $user = $userProvider->createNewUser(
            'username@lighthouse.pro',
            'password',
            'name',
            array(User::ROLE_ADMINISTRATOR),
            'position'
        );

        $this->getDocumentManager()->clear();

        $serializedUser = serialize($user);
        $unserializedUser = unserialize($serializedUser);

        $this->assertNotSame($user, $unserializedUser);

        $refreshedUser = $userProvider->refreshUser($unserializedUser);

        $this->assertEquals($user->id, $refreshedUser->id);
        $this->assertEquals($user->email, $refreshedUser->email);
        $this->assertNotSame($user, $refreshedUser);
    }

    public function testSupportsClass()
    {
        $this->assertTrue($this->getUserProvider()->supportsClass(User::getClassName()));
    }

    public function testNotSupportsClass()
    {
        $this->assertFalse($this->getUserProvider()->supportsClass(Store::getClassName()));
    }

    public function testLoadUserByUsername()
    {
        $this->clearMongoDb();

        $user = $this->getUserProvider()->createNewUser(
            'username@lighthouse.pro',
            'password',
            'name',
            array(User::ROLE_ADMINISTRATOR),
            'position'
        );

        static::rebootKernel();

        $loadedUser = $this->getUserProvider()->loadUserByUsername('username@lighthouse.pro');

        $this->assertEquals($user->id, $loadedUser->id);
        $this->assertNotSame($user, $loadedUser);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameInvalidUsername()
    {
        $this->clearMongoDb();

        $this->getUserProvider()->loadUserByUsername('username');
    }
}
