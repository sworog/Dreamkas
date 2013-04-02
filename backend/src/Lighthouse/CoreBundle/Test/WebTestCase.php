<?php

namespace Lighthouse\CoreBundle\Test;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use AppKernel;

/**
 * @codeCoverageIgnore
 */
class WebTestCase extends BaseTestCase
{
    /**
     * Init app with debug
     * @var bool
     */
    static protected $appDebug = true;

    /**
     *
     */
    protected function setUp()
    {
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

    protected function clearMongoDb()
    {
        /* @var DocumentManager $mongoDb */
        $mongoDb = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $mongoDb->getSchemaManager()->dropDatabases();
    }

    /**
     * @param Crawler $crawler
     * @param array $assertions
     * @param bool $xpath
     */
    protected function runCrawlerAssertions(Crawler $crawler, array $assertions, $xpath = false)
    {
        foreach ($assertions as $selector => $expected) {
            $filtered = ($xpath) ? $crawler->filterXPath($selector) : $crawler->filter($selector);
            $this->assertContains($expected, $filtered->first()->text());
        }
    }
}
