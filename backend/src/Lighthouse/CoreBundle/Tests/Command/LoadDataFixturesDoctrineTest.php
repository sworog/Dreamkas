<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use PHPUnit_Framework_Error_Notice;

class LoadDataFixturesDoctrineTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    public function testDefaultFixtures()
    {
        $project = $this->factory()->user()->getProject();
        $tester = $this->createConsoleTester()->runProjectCommand('doctrine:mongodb:fixtures:load', $project);

        $expected = '  > purging database
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading [30] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadStoresData
  > loading [40] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadCatalogData
';

        $this->assertEquals($expected, $tester->getDisplay());
    }

    /**
     * @dataProvider fixturesProvider
     */
    public function testCustomFixtures($folder, $expectedOutput)
    {
        // FIXME doctrine command produces notice so will suppress it in this test
        $errorNoticeBackup = PHPUnit_Framework_Error_Notice::$enabled;
        PHPUnit_Framework_Error_Notice::$enabled = false;
        $errorReporting = error_reporting();
        error_reporting($errorReporting ^ E_NOTICE);

        $fixturesPath = $this->getContainer()->getParameter('kernel.root_dir');
        $fixturesPath.= '/../src/Lighthouse/CoreBundle/DataFixtures/' . $folder;

        $project = $this->factory()->user()->getProject();

        $tester = $this->createConsoleTester()->runProjectCommand(
            'doctrine:mongodb:fixtures:load',
            $project,
            array('--fixtures' => $fixturesPath)
        );

        error_reporting($errorReporting);
        PHPUnit_Framework_Error_Notice::$enabled = $errorNoticeBackup;
        $this->assertEquals($expectedOutput, $tester->getDisplay());
    }

    /**
     * @return array
     */
    public function fixturesProvider()
    {
        return array(
            'kesko' => array(
                'Kesko',
                '  > purging database
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\Kesko\\KeskoLoadStoresData
'
            ),
            'amn' => array(
                'AMN',
                '  > purging database
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\AMN\\AmnLoadStoresData
'
            ),
            'food' => array(
                'Food',
                '  > purging database
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\Food\\FoodLoadStoresData
'
            ),
        );
    }
}
