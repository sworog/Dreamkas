<?php

namespace Lighthouse\CoreBundle\Tests\Command\User;

use Lighthouse\CoreBundle\Command\User\CreateUser;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserTest extends WebTestCase
{
    /**
     * @var CreateUser
     */
    protected $command;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    protected function setUp()
    {
        $this->clearMongoDb();

        $this->userProvider = $this->getContainer()->get('lighthouse.core.user.provider');
        $serializer = $this->getContainer()->get('serializer');

        $command = new CreateUser();
        $command->setUserProvider($this->userProvider);
        $command->setSerializer($serializer);

        $this->command = $command;
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);

        $input = array(
            'username' => 'admin',
            'password' => 'lighthouse',
            'role' => 'administrator',
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('Creating user...Done', $display);
        $this->assertContains('"username":"admin"', $display);
        $this->assertContains('"role":"administrator"', $display);

        $user = $this->userProvider->loadUserByUsername('admin');

        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\User\\User', $user);
        $this->assertEquals('admin', $user->username);
        $this->assertNotEquals('lighthouse', $user->password);
        $this->assertEquals('administrator', $user->role);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     * @expectedExceptionMessage username:
     */
    public function testUserExists()
    {
        $commandTester = new CommandTester($this->command);

        $input = array(
            'username' => 'admin',
            'password' => 'lighthouse',
            'role' => 'administrator',
        );

        $exitCode1 = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode1);

        $exitCode2 = $commandTester->execute($input);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testMissingParams()
    {
        $commandTester = new CommandTester($this->command);

        $exitCode = $commandTester->execute(array());

        $this->assertEquals(0, $exitCode);
    }
}
