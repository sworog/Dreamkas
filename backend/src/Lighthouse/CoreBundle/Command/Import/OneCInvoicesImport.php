<?php

namespace Lighthouse\CoreBundle\Command\Import;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Integration\OneC\Import\Invoices\InvoicesImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.command.import.onec_invoices_import")
 * @DI\Tag("console.command")
 */
class OneCInvoicesImport extends Command
{
    /**
     * @var InvoicesImporter
     */
    protected $importer;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.onec.import.invoices.importer")
     * })
     * @param InvoicesImporter $importer
     */
    public function __construct(InvoicesImporter $importer)
    {
        parent::__construct('lighthouse:import:invoices:csv');
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Csv file path');
        $this->addOption('batch', null, InputOption::VALUE_OPTIONAL, 'Batch size', 100);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        $batchSize = $input->getOption('batch');

        $this->importer->import($filePath, $batchSize, $output);
    }
}
