<?php

namespace Lighthouse\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearDatabaseCommand extends ContainerAwareCommand
{
    /**
     * @return \MongoDB
     */
    protected function getMongoDb()
    {
        return $this->getContainer()->get('lighthouse_core.db.mongo.db');
    }
    /**
     *
     */
    protected function configure()
    {
        $this->setName("lighthouse:database:clear");
        $this->setDescription("Clears mongo database");
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mongoDb = $this->getMongoDb();

        $output->writeln(sprintf("<info>Clearing mongo database: <comment>%s</comment></info>", $mongoDb));

        $mongoDb->drop();

        $output->writeln("<info>Database successfully cleared.</info>");
    }
}
