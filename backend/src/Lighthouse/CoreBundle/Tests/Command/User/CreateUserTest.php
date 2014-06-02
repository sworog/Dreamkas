<?php

namespace Lighthouse\CoreBundle\Tests\Command\User;

use JMS\Serializer\SerializerInterface;
use Lighthouse\CoreBundle\Command\User\CreateUser;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
    }

    /**
     * @return CreateUser
     */
    protected function getCommand()
    {
        return $this->getContainer()->get('lighthouse.core.command.user.create_user');
    }

    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->getContainer()->get('lighthouse.core.user.provider');
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->getCommand());

        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'role' => 'ROLE_ADMINISTRATOR',
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('Creating user...Done', $display);
        $this->assertContains('"email":"admin@lighthouse.pro"', $display);
        $this->assertContains('"roles":["ROLE_ADMINISTRATOR"]', $display);

        $user = $this->getUserProvider()->loadUserByUsername('admin@lighthouse.pro');

        $this->assertInstanceOf(User::getClassName(), $user);
        $this->assertEquals('admin@lighthouse.pro', $user->email);
        $this->assertNotEquals('lighthouse', $user->password);
        $this->assertContains(User::ROLE_ADMINISTRATOR, $user->roles);
        $this->assertInstanceOf(Project::getClassName(), $user->project);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     * @expectedExceptionMessage email:
     */
    public function testUserExists()
    {
        $commandTester = new CommandTester($this->getCommand());

        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'role' => 'ROLE_ADMINISTRATOR',
        );

        $exitCode1 = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode1);

        $commandTester->execute($input);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Project with id#1 not found
     */
    public function testProjectDoesNotExist()
    {
        $commandTester = new CommandTester($this->getCommand());

        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'role' => 'ROLE_ADMINISTRATOR',
            '--project' => 1
        );

        $commandTester->execute($input);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testMissingParams()
    {
        $commandTester = new CommandTester($this->getCommand());

        $commandTester->execute(array());
    }
}
