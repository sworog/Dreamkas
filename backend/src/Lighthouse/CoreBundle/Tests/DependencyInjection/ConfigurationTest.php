<?php

namespace Lighthouse\DependencyInjection;

use Lighthouse\CoreBundle\DependencyInjection\Configuration;
use Lighthouse\CoreBundle\Test\TestCase;

class ConfigurationTest extends TestCase
{
    public function testGetConfigTreeBuilder()
    {
        $configuration = new Configuration();

        $treeBuilder = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder', $treeBuilder);

        $node = $treeBuilder->buildTree();
        $this->assertInstanceOf('Symfony\\Component\\Config\Definition\NodeInterface', $node);
        $this->assertEquals('lighthouse_core', $node->getName());
    }
}
