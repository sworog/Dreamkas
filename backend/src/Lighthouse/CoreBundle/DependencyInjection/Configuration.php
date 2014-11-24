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

        $this->addPrecisionConfig($nodeBuilder);
        $this->addRoundingConfig($nodeBuilder);
        $this->addSelectelConfig($nodeBuilder);

        return $treeBuilder;
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
                                ->defaultValue('https://auth.selcdn.ru/')
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
