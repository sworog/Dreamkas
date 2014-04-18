<?php

namespace Lighthouse\CoreBundle\Test;

use AppKernel;
use Doctrine\ODM\MongoDB\DocumentManager;
use Karzer\Framework\TestCase\SymfonyWebTestCase;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\KernelInterface;

class ContainerAwareTestCase extends SymfonyWebTestCase
{
    /**
     * Init app with debug
     * @var bool
     */
    protected static $appDebug = false;

    /**
     * @var Factory
     */
    protected $factory;

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
        $kernel = static::getKernel();
        $kernel->shutdown();
        $kernel->boot();
    }

    /**
     * @return KernelInterface
     */
    protected static function getKernel()
    {
        if (null === static::$kernel) {
            static::initKernel();
        }
        static::$kernel->boot();
        return static::$kernel;
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
        return static::getKernel()->getContainer();
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return Factory
     */
    protected function factory()
    {
        if (null === $this->factory) {
            $this->factory = $this->createFactory();
        }
        return $this->factory;
    }

    /**
     * @return Factory
     */
    protected function createFactory()
    {
        return new Factory($this->getContainer());
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
