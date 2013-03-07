<?php

namespace Lighthouse\CoreBundle\Test;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\Container;

class WebTestCase extends BaseTestCase
{
    protected function setUp()
    {
        static::initKernel();
    }

    protected static function initKernel()
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
        /* @var DocumentManager $mongoDb */
        $mongoDb = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $mongoDb->getSchemaManager()->dropDatabases();
    }
}
