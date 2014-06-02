<?php

namespace Lighthouse\CoreBundle\Test\Console;

use Lighthouse\CoreBundle\Document\Project\Project;
use Symfony\Component\Console\Tester\ApplicationTester as BaseApplicationTester;

class ApplicationTester extends BaseApplicationTester
{
    /**
     * @param string $command
     * @param array $input
     * @param array $options
     * @return ApplicationTester
     */
    public function runCommand($command, array $input = array(), $options = array())
    {
        $input = array('command' => $command) + $input;
        return $this->run($input, $options);
    }

    /**
     * @param string $command
     * @param string|Project $project
     * @param array $input
     * @param array $options
     * @return ApplicationTester
     */
    public function runProjectCommand($command, $project, array $input = array(), $options = array())
    {
        $input['--project'] = ($project instanceof Project) ? $project->getName() : $project;
        return $this->runCommand($command, $input, $options);
    }

    /**
     * @param array $input
     * @param array $options
     * @return ApplicationTester
     */
    public function run(array $input, $options = array())
    {
        parent::run($input, $options);
        return $this;
    }
}
