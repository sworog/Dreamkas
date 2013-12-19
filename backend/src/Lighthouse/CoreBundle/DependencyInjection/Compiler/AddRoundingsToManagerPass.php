<?php

namespace Lighthouse\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

class AddRoundingsToManagerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     * @throws ServiceNotFoundException
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('lighthouse.core.rounding.manager')) {

            $definition = $container->getDefinition('lighthouse.core.rounding.manager');

            foreach ($container->findTaggedServiceIds('rounding') as $id => $tagAttributes) {
                $definition->addMethodCall('add', array(new Reference($id)));
            }
        }
    }
}
