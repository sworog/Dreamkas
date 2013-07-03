<?php

namespace Lighthouse\CoreBundle;

use Lighthouse\CoreBundle\Command\CommandManager;
use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddCommandAsServicePass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Types\Type;

class LighthouseCoreBundle extends Bundle
{
    protected $commands = array();

    public function __construct()
    {
        Type::registerType('money', 'Lighthouse\CoreBundle\Types\MongoDB\MoneyType');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddCommandAsServicePass());
    }

    /**
     * @param Application $application
     */
    public function registerCommands(Application $application)
    {
        /* @var CommandManager $commandManager */
        $commandManager = $this->container->get('lighthouse.core.command.manager');
        $application->addCommands($commandManager->getAll());
    }
}
