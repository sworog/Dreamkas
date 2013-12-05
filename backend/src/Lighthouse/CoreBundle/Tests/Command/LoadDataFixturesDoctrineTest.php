<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Command\LoadDataFixturesDoctrine;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Test\TestOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\HttpKernel\KernelInterface;

class LoadDataFixturesDoctrineTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    public function testExecuteThroughApplicationDefaultFixtures()
    {
        /* @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);

        $input = new ArgvInput(
            array(
                'app/console',
                'doctrine:mongodb:fixtures:load'
            )
        );
        $input->setInteractive(false);
        $output = new TestOutput();
        $application->doRun($input, $output);

        $expected = '  > purging database
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading [30] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadStoresData
  > loading [40] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadCatalogData
';

        $this->assertEquals($expected, $output->getDisplay());
    }
}
