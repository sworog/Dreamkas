<?php

namespace Lighthouse\CoreBundle\DataFixtures\ODM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadApiClientData extends ContainerAware implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->createClient('webfront', 'secret');
        $this->createClient('autotests', 'secret');
    }

    /**
     * @return \FOS\OAuthServerBundle\Document\ClientManager
     */
    protected function getClientManager()
    {
        return $this->container->get('fos_oauth_server.client_manager.default');
    }

    /**
     * @param string $randomId
     * @param string $secret
     * @param string $id
     * @return ClientInterface
     */
    protected function createClient($randomId, $secret, $id = null)
    {
        $id = ($id) ?: $randomId;

        $client = $this->getClientManager()->createClient();

        $client->setSecret($secret);
        $client->setRandomId($randomId);
        $client->setId($id);

        $this->getClientManager()->updateClient($client);

        return $client;
    }
}
