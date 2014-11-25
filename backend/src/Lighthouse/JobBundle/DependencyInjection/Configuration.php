<?php

namespace Lighthouse\JobBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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
        $rootNode = $treeBuilder->root('lighthouse_job');

        $nodeBuilder = $rootNode->children();

        $this->addJobConfig($nodeBuilder);

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    protected function addJobConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('worker')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('max_runtime')
                        ->info('Worker max runtime in seconds')
                        ->example(600)
                        ->defaultValue(600)
                    ->end()
                    ->integerNode('reserve_timeout')
                        ->info('Time to wait for a new job in seconds')
                        ->example(60)
                        ->defaultValue(60)
                    ->end()
                ->end()
            ->end()
            ->scalarNode('tube_prefix')
                ->info('Tube name prefix not to mess with another hosts')
                ->defaultValue('')
            ->end()
        ;
    }
}
