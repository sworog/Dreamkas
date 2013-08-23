<?php

namespace Lighthouse\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddJobWorkersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws ServiceNotFoundException
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('lighthouse.core.job.worker.manager')) {
            throw new ServiceNotFoundException('lighthouse.core.job.worker.manager');
        }

        $definition = $container->getDefinition('lighthouse.core.job.worker.manager');

        foreach ($container->findTaggedServiceIds('job.worker') as $id => $tagAttributes) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
 