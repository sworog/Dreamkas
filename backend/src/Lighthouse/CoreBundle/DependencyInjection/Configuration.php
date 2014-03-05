<?php

namespace Lighthouse\CoreBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('lighthouse_core');

        $nodeBuilder = $rootNode->children();

        $this->addJobConfig($nodeBuilder);
        $this->addPrecisionConfig($nodeBuilder);
        $this->addRoundingConfig($nodeBuilder);
        $this->addSelectelConfig($nodeBuilder);

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    protected function addJobConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('job')
                ->addDefaultsIfNotSet()
                ->children()
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
                ->end()
            ->end()
        ;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    protected function addPrecisionConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('precision')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('money')
                        ->defaultValue(2)
                    ->end()
                    ->integerNode('quantity')
                        ->defaultValue(3)
                    ->end()
        ;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    protected function addRoundingConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('rounding')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('default')
                        ->info('Default rounding used in system')
                        ->defaultValue('nearest1')
        ;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    protected function addSelectelConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('selectel')
                ->isRequired()
                ->children()
                    ->arrayNode('auth')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('url')
                                ->defaultValue('https://auth.selcdn.ru')
                            ->end()
                            ->scalarNode('username')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('password')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                    ->scalarNode('container')
                        ->isRequired()
                    ->end()
                    ->variableNode('options')
                        ->defaultValue(array())
                    ->end()
                ->end();

    }
}
