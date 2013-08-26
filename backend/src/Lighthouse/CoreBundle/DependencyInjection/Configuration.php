<?php

namespace Lighthouse\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lighthouse_core');

        $rootNode
            ->children()
                ->arrayNode('job')
                    ->children()
                        ->scalarNode('tube_prefix')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('money')
                    ->children()
                        ->integerNode('precision')
                            ->defaultValue(2)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('rounding')
                    ->children()
                        ->scalarNode('default')
                            ->defaultValue('nearest1')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
