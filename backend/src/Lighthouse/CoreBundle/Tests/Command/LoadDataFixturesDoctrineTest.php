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
        $tester = $this->createConsoleTester()->runCommand(
            'doctrine:mongodb:fixtures:load',
            array('--append' => true)
        );

        $expected = <<<EOF
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading [30] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadStoresData
  > loading [40] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadCatalogData
  > loading [50] Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadSuppliersData

EOF;

        $this->assertEquals($expected, $tester->getDisplay());
    }

    /**
     * @dataProvider fixturesProvider
     * @param string $folder
     * @param string $expectedOutput
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

        $tester = $this->createConsoleTester()->runCommand(
            'doctrine:mongodb:fixtures:load',
            array(
                '--fixtures' => $fixturesPath,
                '--append' => true
            )
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
                <<<EOF
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\Kesko\\KeskoLoadStoresData

EOF
            ),
            'amn' => array(
                'AMN',
                <<<EOF
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\AMN\\AmnLoadStoresData

EOF
            ),
            'food' => array(
                'Food',
                <<<EOF
  > loading [10] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData
  > loading [20] Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData
  > loading [50] Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadSuppliersData
  > loading Lighthouse\\CoreBundle\\DataFixtures\\Food\\FoodLoadStoresData

EOF
            ),
        );
    }
}
