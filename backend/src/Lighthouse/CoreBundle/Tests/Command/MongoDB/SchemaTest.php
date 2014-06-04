<?php

namespace Lighthouse\CoreBundle\Tests\Command\MongoDB;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class SchemaTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
    }

    public function testSymfonyEnvInitCommands()
    {
        $consoleTester = $this->createConsoleTester(true, true)->runCommand('doctrine:mongodb:schema:drop');
        $this->assertEquals(0, $consoleTester->getStatusCode());

        $consoleTester = $this->createConsoleTester(true, true)->runCommand('doctrine:mongodb:schema:create');
        $this->assertEquals(0, $consoleTester->getStatusCode());

        $input = array(
            'email' => 'watchman@lighthouse.pro',
            'password' => 'lighthouse',
            'roles' => array('ROLE_ADMINISTRATOR')
        );

        $consoleTester = $this->createConsoleTester(true, true)->runCommand('lighthouse:user:create', $input);
        $this->assertEquals(0, $consoleTester->getStatusCode());
        $user1 = $this->parseUserData($consoleTester->getDisplay());
        $this->assertNotNull($user1->project->id);

        $input = array(
            'email' => 'owner@lighthouse.pro',
            'password' => 'lighthouse',
        );

        $consoleTester = $this->createConsoleTester(true, true)->runCommand('lighthouse:user:create', $input);
        $this->assertEquals(0, $consoleTester->getStatusCode());
        $user2 = $this->parseUserData($consoleTester->getDisplay());
        $this->assertNotNull($user2->project->id);

        $this->assertNotEquals($user1->project->id, $user2->project->id);

        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $defaultDbName = $dm->getDocumentDatabase(User::getClassName())->getName();
        $databases = $dm->getConnection()->listDatabases();
        $databaseNames = array_map(
            function ($database) {
                return $database['name'];
            },
            $databases['databases']
        );
        $this->assertContains($defaultDbName, $databaseNames);
        $this->assertContains($defaultDbName . '_' . $user1->project->id, $databaseNames);
        $this->assertContains($defaultDbName . '_' . $user2->project->id, $databaseNames);

        $consoleTester = $this->createConsoleTester(true, true)->runCommand('doctrine:mongodb:schema:drop');
        $this->assertEquals(0, $consoleTester->getStatusCode());

        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $defaultDbName = $dm->getDocumentDatabase(User::getClassName())->getName();
        $databases = $dm->getConnection()->listDatabases();
        $databaseNames = array_map(
            function ($database) {
                return $database['name'];
            },
            $databases['databases']
        );
        $this->assertNotContains($defaultDbName, $databaseNames);
        $this->assertNotContains($defaultDbName . '_' . $user1->project->id, $databaseNames);
        $this->assertNotContains($defaultDbName . '_' . $user2->project->id, $databaseNames);
    }

    /**
     * @param string $display
     * @return \stdClass
     */
    protected function parseUserData($display)
    {
        $matches = null;
        if (preg_match('/(\{.*?\})/u', $display, $matches)) {
            return json_decode($matches[1] . '}');
        }
        throw new \RuntimeException('Failed to parse user data');
    }
}
