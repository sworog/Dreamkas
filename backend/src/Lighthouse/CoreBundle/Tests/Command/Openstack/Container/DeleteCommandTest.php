<?php

namespace Lighthouse\CoreBundle\Tests\Command\Openstack\Container;

use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class DeleteCommandTest extends ContainerAwareTestCase
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

    /**
     * @dataProvider providerContainerName
     */
    public function testContainerExists(array $options, $expectedName)
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.no_content'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.no_content'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = $options + array(
            'openstack:container:delete',
        );

        $tester->run($input);

        $display = $tester->getDisplay();

        $this->assertEquals(0, $tester->getStatusCode(), $display);

        $this->assertEquals("Deleting container \"{$expectedName}\" ... Done\n", $display);
        $this->assertCount(4, $mockPlugin->getReceivedRequests());
    }

    /**
     * @return array
     */
    public function providerContainerName()
    {
        return array(
            'default' => array(
                array(),
                'lighthouse_test',
            ),
            'custom' => array(
                array('--name' => 'custom'),
                'custom'
            )
        );
    }

    /**
     * @dataProvider providerContainerName
     */
    public function testContainerDoesNotExist(array $options, $expectedName)
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.not_found'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = $options + array(
            'openstack:container:delete',
        );

        $tester->run($input);

        $display = $tester->getDisplay();

        $this->assertEquals(0, $tester->getStatusCode(), $display);

        $this->assertEquals("Deleting container \"{$expectedName}\" ... Failed - Does not exist\n", $display);
        $this->assertCount(2, $mockPlugin->getReceivedRequests());
    }

    /**
     * @dataProvider providerContainerName
     */
    public function testFailedAuth(array $options, $expectedName)
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.forbidden'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        $tester = $this->getApplicationTester();

        $input = $options + array(
            'openstack:container:delete',
        );

        $tester->run($input);

        $display = $tester->getDisplay();

        $this->assertEquals(1, $tester->getStatusCode(), $display);

        $this->assertStringStartsWith("Deleting container \"{$expectedName}\" ... ", $display);

        $this->assertContains("[Guzzle\\Http\\Exception\\ClientErrorResponseException]", $display);
        $this->assertContains("[status code] 403", $display);
        $this->assertCount(1, $mockPlugin->getReceivedRequests());
    }
}
