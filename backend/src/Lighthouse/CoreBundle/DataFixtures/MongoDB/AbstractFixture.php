<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Lighthouse\CoreBundle\Document\ClassNameable;
use Symfony\Component\DependencyInjection\ContainerAware;

abstract class AbstractFixture extends ContainerAware implements
    FixtureInterface,
    OrderedFixtureInterface,
    ClassNameable
{
    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }

    /**
     * @param string $username
     */
    protected function authenticateProjectByUsername($username)
    {
        $projectContext = $this->container->get('project.context');
        $projectContext->authenticateByUsername($username);
    }
}
