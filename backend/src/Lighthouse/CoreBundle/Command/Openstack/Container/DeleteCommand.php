<?php

namespace Lighthouse\CoreBundle\Command\Openstack\Container;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * @DI\Service("lighthouse.core.command.openstack.delete", parent="lighthouse.core.command.openstack.container")
 * @DI\Tag("console.command")
 */
class DeleteCommand extends AbstractContainerCommand
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
            $output->write(sprintf('Deleting container "%s" ... ', $containerName));
            $container = $this->getStorageService()->getContainer($containerName);
            $container->delete(true);
            $output->writeln('Done');
        } catch (ClientErrorResponseException $e) {
            if (404 == $e->getResponse()->getStatusCode()) {
                $output->writeln(sprintf('Failed - Does not exist', $containerName));
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
        return 'delete';
    }
}
