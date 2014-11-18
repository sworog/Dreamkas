<?php

namespace Lighthouse\JobBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class LighthouseJobExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // job params
        $container->setParameter('lighthouse.job.tube.prefix', $config['tube_prefix']);
        $container->setParameter('lighthouse.job.worker.max_runtime', $config['worker']['max_runtime']);
        $container->setParameter('lighthouse.job.worker.reserve_timeout', $config['worker']['reserve_timeout']);
    }
}
