<?php

namespace Lighthouse\CoreBundle;

use Lighthouse\CoreBundle\DependencyInjection\Compiler\DocumentRepositoryPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

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

        $container->addCompilerPass(new DocumentRepositoryPass());
    }

    /*
    public function registerCommands(Application $application)
    {
        $container = new ContainerBuilder();
        $container->get('lighthouse.core.command.recalculate_average_purchase_price');
        var_dump($container->get('lighthouse.core.command.recalculate_average_purchase_price'));
    }
    */
}
