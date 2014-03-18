<?php

namespace Lighthouse\CoreBundle\Tests\Command\Openstack\Container;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CreateCommandTest extends ContainerAwareTestCase
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

    public function testContainerExists()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);

        $display = $tester->getDisplay();

        $this->assertEquals(0, $tester->getStatusCode(), $display);

        $this->assertEquals("Container \"lighthouse_test\" already exists\n", $display);
        $this->assertCount(2, $mockPlugin->getReceivedRequests());
    }

    public function testContainerDoesNotExists()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.not_found'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.created'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);

        $display = $tester->getDisplay();
        $this->assertEquals(0, $tester->getStatusCode(), $display);

        $this->assertEquals("Container \"lighthouse_test\" does not exist\nTrying to create it ... Done\n", $display);

        $this->assertCount(3, $mockPlugin->getReceivedRequests());
    }

    public function testContainerDoesNotExistAndFailedToCreate()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.not_found'));
        $mockPlugin->addException(new CurlException());

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);
        $display = $tester->getDisplay();
        $this->assertEquals(1, $tester->getStatusCode(), $display);

        $this->assertStringStartsWith(
            "Container \"lighthouse_test\" does not exist\nTrying to create it ...",
            $display
        );

        $this->assertContains(
            '[Guzzle\Http\Exception\CurlException]',
            $display
        );

        $this->assertCount(3, $mockPlugin->getReceivedRequests());
    }

    public function testContainerDoesNotExistAndFailedToCreateIt()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.not_found'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.accepted'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);
        $display = $tester->getDisplay();
        $this->assertEquals(0, $tester->getStatusCode(), $display);

        $this->assertStringStartsWith(
            "Container \"lighthouse_test\" does not exist\nTrying to create it ... Failed",
            $display
        );

        $this->assertCount(3, $mockPlugin->getReceivedRequests());
    }

    public function testAuthFailed()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.forbidden'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = array(
            'openstack:container:create'
        );

        $tester->run($input);

        $display = $tester->getDisplay();
        $this->assertEquals(1, $tester->getStatusCode(), $display);

        $this->assertContains("[Guzzle\\Http\\Exception\\ClientErrorResponseException]", $display);

        $this->assertCount(1, $mockPlugin->getReceivedRequests());
    }
}
