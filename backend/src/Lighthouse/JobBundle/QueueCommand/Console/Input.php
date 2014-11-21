<?php

namespace Lighthouse\JobBundle\QueueCommand\Console;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;

class Input extends ArgvInput
{
    /**
     * @param array $command
     * @param InputDefinition $definition
     */
    public function __construct($command, InputDefinition $definition = null)
    {
        parent::__construct(
            $this->convertCommandToArgv($command),
            $definition
        );
    }

    /**
     * @param string $command
     * @return array
     */
    public function convertCommandToArgv($command)
    {
        $args = explode(' ', 'console ' . $command);
        return $args;
    }
}
