<?php

namespace Lighthouse\CoreBundle\Tests\DependencyInjection\Compiler;

use Lighthouse\CoreBundle\DependencyInjection\Compiler\AddReferenceProvidersPass;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AddReferenceProvidersPassTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    public function testMissingAlias()
    {
        $referenceManagerDefinition = new Definition();

        $referenceProviderWithoutAlias = new Definition();
        $referenceProviderWithoutAlias->addTag('reference.provider');

        $builder = new ContainerBuilder();
        $builder->setDefinition('reference.provider.without.alias', $referenceProviderWithoutAlias);
        $builder->setDefinition('lighthouse.core.mongodb.reference.manager', $referenceManagerDefinition);

        $compilerPass = new AddReferenceProvidersPass();
        $compilerPass->process($builder);
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testNoReferenceManagerDefinition()
    {
        $referenceProviderWithoutAlias = new Definition();
        $referenceProviderWithoutAlias->addTag('reference.provider');

        $builder = new ContainerBuilder();
        $builder->setDefinition('reference.provider.without.alias', $referenceProviderWithoutAlias);

        $compilerPass = new AddReferenceProvidersPass();
        $compilerPass->process($builder);
    }

    public function testNoTaggedServices()
    {
        $referenceManagerDefinition = new Definition();

        $builder = new ContainerBuilder();
        $builder->setDefinition('lighthouse.core.mongodb.reference.manager', $referenceManagerDefinition);

        $this->assertCount(0, $referenceManagerDefinition->getMethodCalls());

        $compilerPass = new AddReferenceProvidersPass();
        $compilerPass->process($builder);

        $this->assertCount(0, $referenceManagerDefinition->getMethodCalls());
    }

    public function testTaggedServicesAreAvailable()
    {
        $referenceManagerDefinition = new Definition();

        $builder = new ContainerBuilder();
        $builder->setDefinition('lighthouse.core.mongodb.reference.manager', $referenceManagerDefinition);

        $referenceProvider1 = new Definition();
        $referenceProvider1->addTag('reference.provider', array('alias' => 'alias1'));

        $referenceProvider2 = new Definition();
        $referenceProvider2->addTag('reference.provider', array('alias' => 'alias2'));

        $referenceProvider3 = new Definition();
        $referenceProvider3->addTag('reference.provider', array('alias' => 'alias3'));

        $builder->addDefinitions(array($referenceProvider1, $referenceProvider2, $referenceProvider3));

        $this->assertCount(0, $referenceManagerDefinition->getMethodCalls());

        $compilerPass = new AddReferenceProvidersPass();
        $compilerPass->process($builder);

        $providers = $referenceManagerDefinition->getArgument(0);
        $this->assertCount(3, $providers);
        $this->assertArrayHasKey('alias1', $providers);
        $this->assertInstanceOf('Symfony\\Component\\DependencyInjection\\Reference', $providers['alias1']);
        $this->assertArrayHasKey('alias2', $providers);
        $this->assertInstanceOf('Symfony\\Component\\DependencyInjection\\Reference', $providers['alias2']);
        $this->assertArrayHasKey('alias3', $providers);
        $this->assertInstanceOf('Symfony\\Component\\DependencyInjection\\Reference', $providers['alias3']);
    }
}
