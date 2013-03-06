<?php

namespace Lighthouse\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.create_database")
 */
class ClearDatabaseCommand extends ContainerAwareCommand
{
    /**
     * @var \MongoDB
     */
    protected $mongoDb;

    /**
     * @DI\InjectParams({"mongoDb" = @DI\Inject("lighthouse.core.db.mongo.db")})
     * @param MongoDb $mongoDB
     */
    public function setMongoDb(\MongoDB $mongoDb)
    {
        $this->mongoDb = $mongoDb;
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
        $mongoDb = $this->mongoDb;

        $output->writeln(sprintf("<info>Clearing mongo database: <comment>%s</comment></info>", $mongoDb));

        $mongoDb->drop();

        $output->writeln("<info>Database successfully cleared.</info>");
    }
}
