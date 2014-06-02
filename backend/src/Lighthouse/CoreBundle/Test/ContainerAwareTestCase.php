<?php

namespace Lighthouse\CoreBundle\Test;

use LighthouseKernel;
use Karzer\Framework\TestCase\SymfonyWebTestCase;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
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
    private $factory;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$appDebug = (boolean) getenv('SYMFONY_DEBUG') ?: false;
    }

    protected function tearDown()
    {
        static::shutdownKernel();
        $this->factory = null;
    }

    /**
     * @return LighthouseKernel
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

    protected static function shutdownKernel()
    {
        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }
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
     * @return LighthouseKernel
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
     * @return \Lighthouse\CoreBundle\MongoDB\DocumentManager
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
            $this->factory = new Factory(static::createKernel()->boot()->getContainer());
        }
        return $this->factory;
    }

    protected function clearMongoDb()
    {
        $dm = $this->getDocumentManager();
        $dm->getSchemaManager()->dropProjectDatabases();
        $dm->getSchemaManager()->dropGlobalCollections();
        $dm->getSchemaManager()->createGlobalCollections();
        $dm->getSchemaManager()->ensureGlobalIndexes();
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

    protected function markTestBroken()
    {
        $this->markTestSkipped('Broken');
    }

    /**
     * @param bool $catchExceptions
     * @param bool $reboot
     * @return Application
     */
    protected function createConsoleApplication($catchExceptions = true, $reboot = false)
    {
        if ($reboot) {
            static::shutdownKernel();
        }
        $application = new Application(static::getKernel());
        $application->setCatchExceptions($catchExceptions);
        $application->setAutoExit(false);

        return $application;
    }

    /**
     * @param bool $catchExceptions
     * @param bool $reboot
     * @return ApplicationTester
     */
    protected function createConsoleTester($catchExceptions = true, $reboot = false)
    {
        $application = $this->createConsoleApplication($catchExceptions, $reboot);
        $tester = new ApplicationTester($application);
        return $tester;
    }
}
