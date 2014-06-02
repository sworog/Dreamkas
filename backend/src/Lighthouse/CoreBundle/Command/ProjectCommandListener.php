<?php

namespace Lighthouse\CoreBundle\Command;

use Lighthouse\CoreBundle\Security\Project\ProjectContext;
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
     * @var ProjectContext
     */
    protected $projectContext;

    /**
     * List of command names to be executed in project context
     * @var array
     */
    protected $projectableCommands = array(
        'doctrine:mongodb:fixtures:load',
    );

    /**
     * @DI\InjectParams({
     *      "projectContext" = @DI\Inject("project.context")
     * })
     * @param ProjectContext $projectContext
     */
    public function __construct(ProjectContext $projectContext)
    {
        $this->projectContext = $projectContext;
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
        $this->projectContext->authenticateByProjectName($projectId);
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function supports(Command $command)
    {
        if ($command instanceof ProjectableCommand) {
            return true;
        } elseif (in_array($command->getName(), $this->projectableCommands)) {
            return true;
        } else {
            return false;
        }
    }
}
