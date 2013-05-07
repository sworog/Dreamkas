<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Command\CommandManager;
use Symfony\Component\Console\Command\Command;

class CommandManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $command1 = new Command('command:one');
        $command2 = new Command('command:two');

        $commandManager = new CommandManager();

        $commandManager->add($command1);
        $commandManager->add($command2);

        $commands = $commandManager->getAll();

        $this->assertInternalType('array', $commands);
        $this->assertCount(2, $commands);

        $this->assertCommand($commands, 'command:one', $command1);
        $this->assertCommand($commands, 'command:two', $command2);
    }

    /**
     * @param array $commands
     * @param stirng $commandName
     * @param Command $command
     */
    protected function assertCommand(array $commands, $commandName, Command $command)
    {
        $this->assertArrayHasKey($commandName, $commands);
        $this->assertInstanceOf('Symfony\\Component\\Console\\Command\\Command', $commands[$commandName]);
        $this->assertSame($command, $commands[$commandName]);
    }
}
