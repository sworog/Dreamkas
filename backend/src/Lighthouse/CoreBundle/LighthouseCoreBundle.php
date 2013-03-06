<?php

namespace Lighthouse\CoreBundle;

use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LighthouseCoreBundle extends Bundle
{
    /**
     * Register container aware commands
     *
     * @param Application $application An Application instance
     */
    public function registerCommands(Application $application)
    {
        parent::registerCommands($application);

        $application->add($this->container->get('lighthouse.core.command.create_database'));
    }
}
