<?php

namespace Lighthouse\CoreBundle;

use Lighthouse\CoreBundle\DependencyInjection\Compiler\DocumentRepositoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class LighthouseCoreBundle extends Bundle
{
    public function __construct()
    {
        Type::registerType('money', 'Lighthouse\CoreBundle\Types\MongoDB\MoneyType');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DocumentRepositoryPass());
    }
}
