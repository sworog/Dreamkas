<?php

namespace Lighthouse\CoreBundle\Command\Auth;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use OAuth2\OAuth2;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.create_client")
 * @DI\Tag("console.command")
 */
class CreateClient extends Command
{
    const ARGUMENT_SECRET = 'secret';
    const ARGUMENT_PUBLIC_ID = 'public-id';

    /**
     * @var ClientManagerInterface
     */
    protected $clientManager;

    protected function configure()
    {
        $this
            ->setName('lighthouse:auth:client:create')
            ->setDescription('Create API client')
            ->addArgument(self::ARGUMENT_SECRET, InputArgument::OPTIONAL, 'Client secret')
            ->addArgument(self::ARGUMENT_PUBLIC_ID, InputArgument::OPTIONAL, 'Set custom public-id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->clientManager->createClient();

        $secret = $input->getArgument(self::ARGUMENT_SECRET);
        if ($secret) {
            $client->setSecret($secret);
        }

        $publicId = $input->getArgument(self::ARGUMENT_PUBLIC_ID);
        if ($publicId) {
            $client->setRandomId($publicId);
            $client->setId($publicId);
        }

        $this->clientManager->updateClient($client);

        $output->writeln(
            sprintf(
                "Client created: PublicID: <info>%s</info>, Secret: <info>%s</info>, Grant types: <info>%s</info>",
                $client->getPublicId(),
                $client->getSecret(),
                implode('</info>, <info>', $client->getAllowedGrantTypes())
            )
        );

        return 0;
    }

    /**
     * @DI\InjectParams({
     *      "clientManager" = @DI\Inject("fos_oauth_server.client_manager")
     * })
     * @param ClientManagerInterface $clientManager
     */
    public function setClientManager(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }
}
