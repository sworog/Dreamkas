<?php


namespace Lighthouse\CoreBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\Container;

class WebTestCase extends BaseTestCase
{
    protected function setUp()
    {
        static::initKernel();
    }

    static protected function initKernel()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return static::$kernel->getContainer();
    }

    protected function clearMongoDb()
    {
        /* @var \MongoDB $mongoDb */
        $mongoDb = $this->getContainer()->get('lighthouse.core.db.mongo.db');
        $mongoDb->drop();
    }
}