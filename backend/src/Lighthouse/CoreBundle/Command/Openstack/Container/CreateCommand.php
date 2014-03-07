<?php

namespace Lighthouse\CoreBundle\Command\Openstack\Container;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * @DI\Service("lighthouse.core.command.openstack.create", parent="lighthouse.core.command.openstack.container")
 * @DI\Tag("console.command")
 */
class CreateCommand extends AbstractContainerCommand
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $containerName = $input->getOption('name');
        try {
            $this->storageService->getContainer($containerName);
            $output->writeln(sprintf('Container "%s" already exists', $containerName));
        } catch (ClientErrorResponseException $e) {
            if (404 == $e->getResponse()->getStatusCode()) {
                $output->writeln(sprintf('Container "%s" does not exist', $containerName));
                $output->write('Trying to create it ... ');
                $container = $this->storageService->createContainer($containerName, $this->defaultMetaData);
                if ($container) {
                    $output->writeln('Done');
                } else {
                    $output->writeln('Failed');
                }
            } else {
                throw $e;
            }
        }
    }

    /**
     * @return string
     */
    protected function getOp()
    {
        return 'create';
    }
}
