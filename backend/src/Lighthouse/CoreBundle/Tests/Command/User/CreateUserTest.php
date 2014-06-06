<?php

namespace Lighthouse\CoreBundle\Tests\Command\User;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class CreateUserTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
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
        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'roles' => array('ROLE_ADMINISTRATOR'),
        );

        $commandTester = $this->createConsoleTester()->runCommand('lighthouse:user:create', $input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $display = $commandTester->getDisplay();
        $this->assertContains('Creating user...Done', $display);
        $this->assertContains('"email":"admin@lighthouse.pro"', $display);
        $this->assertContains('"roles":["ROLE_ADMINISTRATOR"]', $display);

        $user = $this->getUserProvider()->loadUserByUsername('admin@lighthouse.pro');

        $this->assertInstanceOf(User::getClassName(), $user);
        $this->assertEquals('admin@lighthouse.pro', $user->email);
        $this->assertNotEquals('lighthouse', $user->password);
        $this->assertContains(User::ROLE_ADMINISTRATOR, $user->roles);
        $this->assertEquals('ROLE_ADMINISTRATOR', $user->position);
        $this->assertInstanceOf(Project::getClassName(), $user->project);
    }

    public function testCreateUserWithMultipleRoles()
    {
        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'roles' => array('ROLE_COMMERCIAL_MANAGER', 'ROLE_STORE_MANAGER', 'ROLE_DEPARTMENT_MANAGER'),
        );

        $commandTester = $this->createConsoleTester(false)->runCommand('lighthouse:user:create', $input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $display = $commandTester->getDisplay();
        $this->assertContains('Creating user...Done', $display);
        $this->assertContains('"email":"admin@lighthouse.pro"', $display);
        $this->assertContains(
            '"roles":["ROLE_COMMERCIAL_MANAGER","ROLE_STORE_MANAGER","ROLE_DEPARTMENT_MANAGER"]',
            $display
        );

        $user = $this->getUserProvider()->loadUserByUsername('admin@lighthouse.pro');

        $this->assertInstanceOf(User::getClassName(), $user);
        $this->assertSame($user->roles, $input['roles']);
        $this->assertEquals('ROLE_COMMERCIAL_MANAGER', $user->position);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\ValidationFailedException
     * @expectedExceptionMessage email:
     */
    public function testUserExists()
    {
        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            array('roles' => 'ROLE_ADMINISTRATOR'),
        );

        $commandTester = $this->createConsoleTester(false, true);

        $exitCode = $commandTester->runCommand('lighthouse:user:create', $input)->getStatusCode();
        $this->assertEquals(0, $exitCode);

        $commandTester->runCommand('lighthouse:user:create', $input);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Project with id#1 not found
     */
    public function testProjectDoesNotExist()
    {
        $input = array(
            'email' => 'admin@lighthouse.pro',
            'password' => 'lighthouse',
            'roles' => array('ROLE_ADMINISTRATOR'),
            '--project' => 1
        );

        $this->createConsoleTester(false)->runCommand('lighthouse:user:create', $input);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testMissingParams()
    {
        $this->createConsoleTester(false)->runCommand('lighthouse:user:create', array());
    }
}
