<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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

    /**
     * @param object $object
     * @param array $data
     */
    protected function populate($object, array $data)
    {
        foreach ($data as $name => $value) {
            $this->getPropertyAccessor()->setValue($object, $name, $value);
        }
    }

    /**
     * @return PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        return $this->container->get('property_accessor');
    }
}
