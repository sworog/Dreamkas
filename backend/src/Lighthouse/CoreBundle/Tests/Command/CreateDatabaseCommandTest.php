<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Command\ClearDatabaseCommand;
use Symfony\Component\Console\Application;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateDatabaseCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $command = $this->getContainer()->get('lighthouse.core.command.create_database');

        $application = new Application();
        $application->add($command);

        $command = $application->find('lighthouse:database:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertContains('Database successfully cleared', $commandTester->getDisplay());
    }
}