<?php

namespace Lighthouse\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.manager")
 */
class CommandManager
{
    /**
     * @var Command[]
     */
    protected $commands = array();

    /**
     * @param Command $command
     */
    public function add(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @return Command[]
     */
    public function getAll()
    {
        return $this->commands;
    }
}
