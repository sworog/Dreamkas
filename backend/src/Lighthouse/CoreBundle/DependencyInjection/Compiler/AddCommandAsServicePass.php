<?php

namespace Lighthouse\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

class AddCommandAsServicePass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('lighthouse.core.command.manager')) {
            throw new ServiceNotFoundException('lighthouse.core.command.manager');
        }

        $definition = $container->getDefinition('lighthouse.core.command.manager');

        foreach ($container->findTaggedServiceIds('console.command') as $id => $tagAttributes) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
