<?php

namespace Lighthouse\CoreBundle\Command\Auth;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.auth.list_client")
 * @DI\Tag("console.command")
 */
class ListClient extends Command
{
    /**
     * @var DocumentRepository
     */
    protected $clientRepository;

    protected function configure()
    {
        $this
            ->setName('lighthouse:auth:client:list')
            ->setDescription('List API clients');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clients = $this->clientRepository->findAll();

        foreach ($clients as $client) {
            $output->writeln(
                sprintf(
                    "Client PublicID: <info>%s</info>, Secret: <info>%s</info>, Grant types: <info>%s</info>",
                    $client->getPublicId(),
                    $client->getSecret(),
                    implode('</info>, <info>', $client->getAllowedGrantTypes())
                )
            );
        }

        return 0;
    }

    /**
     * @DI\InjectParams({
     *      "clientRepository" = @DI\Inject("lighthouse.core.document.repository.client")
     * })
     * @param ObjectRepository $clientRepository
     */
    public function setClientRepository(ObjectRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
}
