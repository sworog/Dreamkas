<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Document\ClientManager;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Lighthouse\CoreBundle\Document\Auth\Client;

class LoadApiClientData extends AbstractFixture
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
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @return ClientManager
     */
    protected function getClientManager()
    {
        return $this->container->get('fos_oauth_server.client_manager.default');
    }

    /**
     * @param string $publicId
     * @param string $secret
     * @return ClientInterface
     */
    protected function createClient($publicId, $secret)
    {
        /* @var ClientInterface|Client $client */
        $client = $this->getClientManager()->createClient();

        $client->setSecret($secret);
        $client->setRandomId($publicId);
        $client->setId($publicId);

        $this->getClientManager()->updateClient($client);

        return $client;
    }
}
