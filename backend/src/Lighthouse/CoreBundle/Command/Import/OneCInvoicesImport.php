<?php

namespace Lighthouse\CoreBundle\Command\Import;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Integration\OneC\Import\Invoices\InvoicesImporter;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
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
        $this->addOption('import-date', null, InputOption::VALUE_OPTIONAL, 'Invoice date to be imported to');
        $this->addOption('original-date', null, InputOption::VALUE_OPTIONAL, 'Original invoice date');
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
        $importDate = $input->getOption('import-date');
        $originalDate = $input->getOption('original-date');
        $period = $this->getDatePeriod($importDate, $originalDate);

        $this->importer->import($filePath, $batchSize, $output, $period);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return DatePeriod
     */
    protected function getDatePeriod($startDate, $endDate)
    {
        if (null !== $startDate && null !== $endDate) {
            return new DatePeriod($startDate, $endDate);
        } else {
            return null;
        }
    }
}
