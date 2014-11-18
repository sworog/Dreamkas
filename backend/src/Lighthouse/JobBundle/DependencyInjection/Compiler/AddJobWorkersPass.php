<?php

namespace Lighthouse\JobBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

class AddJobWorkersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws ServiceNotFoundException
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('lighthouse.job.worker.manager')) {

            $definition = $container->getDefinition('lighthouse.job.worker.manager');

            foreach ($container->findTaggedServiceIds('job.worker') as $id => $tagAttributes) {
                $definition->addMethodCall('add', array(new Reference($id)));
            }
        }
    }
}
