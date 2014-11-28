<?php

namespace Lighthouse\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ModifyNewRelicRequestListenerPriority implements CompilerPassInterface
{
    /**
     * Modify New Relic request listener priority, to start transaction before http firewall authentication
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('ekino.new_relic.request_listener');
        $tags = $definition->getTags();
        $tags['kernel.event_listener'][0]['priority'] = 12;
        $definition->setTags($tags);
    }
}
