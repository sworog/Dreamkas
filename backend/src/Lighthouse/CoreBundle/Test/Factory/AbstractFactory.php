<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractFactory extends ContainerAwareFactory
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param ContainerInterface $container
     * @param Factory $factory
     */
    public function __construct(ContainerInterface $container, Factory $factory)
    {
        parent::__construct($container);
        $this->factory = $factory;
    }
}
