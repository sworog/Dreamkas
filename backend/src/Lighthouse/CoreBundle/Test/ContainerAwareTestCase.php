<?php

namespace Lighthouse\CoreBundle\Test;

use AppKernel;
use Doctrine\ODM\MongoDB\DocumentManager;
use Karzer\Framework\TestCase\SymfonyWebTestCase;
use Lighthouse\CoreBundle\Job\JobManager;
use Symfony\Component\DependencyInjection\Container;

class ContainerAwareTestCase extends SymfonyWebTestCase
{
    /**
     * Init app with debug
     * @var bool
     */
    protected static $appDebug = false;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$appDebug = (boolean) getenv('SYMFONY_DEBUG') ?: false;
    }

    /**
     * @return AppKernel
     */
    protected static function initKernel()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        return static::$kernel;
    }

    protected static function rebootKernel()
    {
        static::$kernel->shutdown();
        static::$kernel->boot();
    }

    /**
     * @param array $options
     * @return AppKernel
     */
    protected static function createKernel(array $options = array())
    {
        $options['debug'] = isset($options['debug']) ? $options['debug'] : static::$appDebug;
        return parent::createKernel($options);
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return static::initKernel()->getContainer();
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    protected function clearMongoDb()
    {
        $mongoDb = $this->getDocumentManager();
        $mongoDb->getSchemaManager()->dropCollections();
        $mongoDb->getSchemaManager()->createCollections();
        $mongoDb->getSchemaManager()->ensureIndexes();
    }

    protected function clearJobs()
    {
        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');
        $jobManager->startWatchingTubes()->purgeTubes()->stopWatchingTubes();
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function getFixtureFilePath($filePath)
    {
        return __DIR__ . '/../Tests/Fixtures/' . $filePath;
    }
}
