<?php

namespace Lighthouse\CoreBundle\Tests\Command\Openstack;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CreateContainerCommandTest extends ContainerAwareTestCase
{
    /**
     * @return ApplicationTester
     */
    protected function getApplicationTester()
    {
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        return new ApplicationTester($application);
    }

    public function testExecute()
    {
        $this->markTestIncomplete();
        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);
        $this->assertEquals(0, $tester->getStatusCode(), $tester->getDisplay());

        $this->assertEquals('', $tester->getDisplay());
    }
}
