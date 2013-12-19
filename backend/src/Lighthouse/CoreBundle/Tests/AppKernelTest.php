<?php

namespace Lighthouse\CoreBundle\Tests;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use AppKernel;

class AppKernelTest extends ContainerAwareTestCase
{
    /**
     * @runInSeparateProcess
     * @dataProvider bootDataProvider
     * @param string $env
     * @param boolean $debug
     */
    public function testBoot($env, $debug)
    {
        $filesystem = $this->getContainer()->get('filesystem');

        $tmpDir = '/tmp/' . uniqid('lighthouse-app-kernel-');
        $filesystem->mkdir($tmpDir);

        $kernel = new AppKernel($env, $debug);
        $kernel->setCacheDir($tmpDir);

        $kernel->boot();

        $filesystem->remove($tmpDir);
    }

    /**
     * @return array
     */
    public function bootDataProvider()
    {
        return array(
            'dev debug' => array('dev', true),
            'dev no-debug' => array('dev', false),
            'staging no-debug' => array('staging', false),
            'production no-debug' => array('production', false),
        );
    }
}
