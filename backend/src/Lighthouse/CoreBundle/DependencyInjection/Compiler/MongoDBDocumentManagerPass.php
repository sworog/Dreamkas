<?php

namespace Lighthouse\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MongoDBDocumentManagerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $documentManagerIds = $container->findTaggedServiceIds('doctrine_mongodb.odm.document_manager');
        foreach ($documentManagerIds as $id => $tagAttributes) {
            $definition = $container->getDefinition($id);

            $definition->addMethodCall('setContainer', array(new Reference('service_container')));

            $configurationServiceId = $definition->getArgument(1);
            $configurationDefinition = $container->getDefinition($configurationServiceId);
            $configurationDefinition->addMethodCall(
                'setClassMetadataFactoryName',
                array('%doctrine_mongodb.odm.metadata_factory.class%')
            );
        }
    }
}
