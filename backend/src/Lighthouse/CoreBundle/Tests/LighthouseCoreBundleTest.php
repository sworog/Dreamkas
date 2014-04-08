<?php

namespace Lighthouse\CoreBundle\Tests;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class LighthouseCoreBundleTest extends ContainerAwareTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCacheWarmUp()
    {
        $filesystem = $this->getContainer()->get('filesystem');
        $cacheDir = sys_get_temp_dir() . '/' . uniqid('lighthouse-cache-warm-up-', true);
        if ($filesystem->exists($cacheDir)) {
            $filesystem->remove($cacheDir);
        }
        $filesystem->mkdir($cacheDir);
        $warmer = $this->getContainer()->get('cache_warmer');
        $warmer->enableOptionalWarmers();
        $warmer->warmUp($cacheDir);
    }
}
