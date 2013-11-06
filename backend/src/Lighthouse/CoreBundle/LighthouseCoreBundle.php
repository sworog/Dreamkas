<?php

namespace Lighthouse\CoreBundle;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Lighthouse\CoreBundle\Command\CommandManager;
use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddCommandAsServicePass;
use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddJobWorkersPass;
use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddReferenceProvidersPass;
use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddRoundingsToManagerPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Types\Type;
use ReflectionProperty;

class LighthouseCoreBundle extends Bundle
{
    public function __construct()
    {
        $this->registerMongoTypes();
        $this->addStreamWrappers();
    }

    protected function registerMongoTypes()
    {
        Type::registerType('money', 'Lighthouse\\CoreBundle\\Types\\MongoDB\\MoneyType');
        Type::registerType('timestamp', 'Lighthouse\\CoreBundle\\Types\\MongoDB\\TimestampType');
    }

    protected function addStreamWrappers()
    {
        if (!in_array('smb', stream_get_wrappers())) {
            stream_wrapper_register('smb', 'Lighthouse\\CoreBundle\\Samba\\SambaStreamWrapper');
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddCommandAsServicePass());
        $container->addCompilerPass(new AddRoundingsToManagerPass());
        $container->addCompilerPass(new AddReferenceProvidersPass());
        $container->addCompilerPass(new AddJobWorkersPass());
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

    public function boot()
    {
        $this->modifyContainerParameters();
        $this->changeMongoDbConnectionsDbNames();
    }

    protected function modifyContainerParameters()
    {
        if (isset($_SERVER['SYMFONY__KERNEL__POOL_POSITION_NAME'])) {
            $postfix = $_SERVER['SYMFONY__KERNEL__POOL_POSITION_NAME'];
            $this->modifyContainerParameter('lighthouse.core.job.tube.prefix', $postfix);
        }
    }

    /**
     * @param string $name
     * @param string $appendValue
     * @return bool
     */
    protected function modifyContainerParameter($name, $appendValue)
    {
        if ($this->container->hasParameter($name)) {
            $parametersReflection = new \ReflectionProperty($this->container, 'parameters');
            $parametersReflection->setAccessible(true);
            $parameters = $parametersReflection->getValue($this->container);
            $parameters[$name].= $appendValue;
            $parametersReflection->setValue($this->container, $parameters);
            return true;
        } else {
            return false;
        }
    }

    protected function changeMongoDbConnectionsDbNames()
    {
        if (isset($_SERVER['SYMFONY__KERNEL__POOL_POSITION_NAME'])) {
            /* @var ManagerRegistry $odm */
            $odm = $this->container->get('doctrine_mongodb');
            /* @var Connection $connection */
            foreach ($odm->getConnections() as $connection) {
                /* @var Configuration $configuration */
                $configuration = $connection->getConfiguration();
                $defaultDb = $configuration->getDefaultDB();
                $defaultDb.= $_SERVER['SYMFONY__KERNEL__POOL_POSITION_NAME'];
                $configuration->setDefaultDB($defaultDb);
            }
        }
    }
}
