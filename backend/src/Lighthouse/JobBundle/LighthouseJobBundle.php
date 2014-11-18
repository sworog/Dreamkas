<?php

namespace Lighthouse\JobBundle;

use Lighthouse\JobBundle\DependencyInjection\Compiler\AddJobWorkersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LighthouseJobBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddJobWorkersPass());
    }
}
