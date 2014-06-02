<?php

namespace Lighthouse\CoreBundle\Command;

use Lighthouse\CoreBundle\Security\Project\ProjectAuthenticationProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputOption;

/**
 * @DI\Service("lighthouse.core.command.project_listener")
 */
class ProjectCommandListener
{
    /**
     * @var ProjectAuthenticationProvider
     */
    protected $provider;

    /**
     * @var array
     */
    protected $projectableCommands = array();

    /**
     * @DI\InjectParams({
     *      "provider" = @DI\Inject("lighthouse.core.security.project.authentication_provider")
     * })
     * @param ProjectAuthenticationProvider $provider
     */
    public function __construct(ProjectAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @DI\Observe("console.command", priority=0)
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $input = $event->getInput();

        if (!$this->supports($command)) {
            return;
        }

        $command->addOption('project', null, InputOption::VALUE_REQUIRED, 'Project ID');

        if (!$input->hasParameterOption('--project')) {
            throw new \InvalidArgumentException('Required option --project is missing');
        }

        $projectId = $event->getInput()->getParameterOption('--project');
        $this->provider->authenticateByProjectName($projectId);
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function supports(Command $command)
    {
        if ($command instanceof ProjectableCommand) {
            return true;
        } elseif (in_array(get_class($command), $this->projectableCommands)) {
            return true;
        } else {
            return false;
        }
    }
}
