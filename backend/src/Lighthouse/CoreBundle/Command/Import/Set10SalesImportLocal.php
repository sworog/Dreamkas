<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Util\File\SortableDirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Exception;
use SplFileInfo;

/**
 * @DI\Service("lighthouse.core.command.import.set10_sales_import_local")
 * @DI\Tag("console.command")
 */
class Set10SalesImportLocal extends Command
{
    /**
     * @var SalesImporter
     */
    protected $importer;

    /**
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import.sales.importer"),
     *      "logRepository" = @DI\Inject("lighthouse.core.document.repository.log")
     * })
     * @param SalesImporter $importer
     * @param LogRepository $logRepository
     */
    public function __construct(
        SalesImporter $importer,
        LogRepository $logRepository
    ) {
        parent::__construct();

        $this->importer = $importer;
        $this->logRepository = $logRepository;
    }

    protected function configure()
    {
        $this
            ->setName('lighthouse:import:sales:local')
            ->setDescription('Import local Set10 purchases xml')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to xml file or directory')
            ->addOption('batch-size', null, InputOption::VALUE_OPTIONAL, 'Batch size', 1000)
            ->addOption('start-date', null, InputOption::VALUE_OPTIONAL, 'Date of first check')
            ->addOption('end-date', null, InputOption::VALUE_OPTIONAL, 'End date')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'End date')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        $batchSize = $input->getOption('batch-size');
        $startDate = $input->getOption('start-date');
        $endDate = $input->getOption('end-date');
        $dryRun = $input->getOption('dry-run');

        $datePeriod = $this->getDatePeriod($startDate, $endDate);
        $files = $this->getXmlFiles($filePath);
        $filesCount = count($files);

        $output->writeln(sprintf('Found %d files', $filesCount));

        foreach ($files as $file) {
            if ($dryRun) {
                $this->checkDates($file, $output, $datePeriod);
            } else {
                $this->importFile($file, $output, $batchSize, $datePeriod);
            }
        }

        $output->writeln('');
        $output->writeln('Finished importing');
    }

    /**
     * @param SplFileInfo $file
     * @param OutputInterface $output
     * @param int $batchSize
     * @param DatePeriod $datePeriod
     */
    protected function importFile(
        SplFileInfo $file,
        OutputInterface $output,
        $batchSize = null,
        DatePeriod $datePeriod = null
    ) {
        try {
            $output->writeln(sprintf('Importing "%s"', $file->getFilename()));
            $parser = new SalesXmlParser($file->getPathname());
            $this->importer->import($parser, $output, $batchSize, $datePeriod);
            foreach ($this->importer->getErrors() as $error) {
                $this->logException($error['exception'], $file);
            }
        } catch (Exception $e) {
            $this->logException($e, $file);
            $output->writeln(sprintf('<error>Failed to import sales</error>: %s', $e->getMessage()));
        }
    }

    /**
     * @param SplFileInfo $file
     * @param OutputInterface $output
     * @param DatePeriod $datePeriod
     */
    protected function checkDates(SplFileInfo $file, OutputInterface $output, DatePeriod $datePeriod = null)
    {
        $output->writeln(sprintf('Checking "%s"', $file->getFilename()));
        try {
            $parser = new SalesXmlParser($file->getPathname());
            $purchaseElement = $parser->readNextElement();
        } catch (Exception $e) {
            $output->writeln(sprintf('<error>Failed to parse xml: %s</error>', $e->getMessage()));
            return;
        }

        if (false === $purchaseElement) {
            $output->writeln('No receipts found');
            return;
        }

        $receiptDate = $purchaseElement->getSaleDateTime();
        $importDate = clone $receiptDate;
        if ($datePeriod) {
            $importDate = $importDate->add($datePeriod->diff());
        }

        $output->writeln(
            sprintf(
                'First receipt date: "%s" will be shifted to "%s"',
                $receiptDate->format(\DateTime::ISO8601),
                $importDate->format(\DateTime::ISO8601)
            )
        );
    }

    /**
     * @param Exception $e
     * @param SplFileInfo $file
     */
    protected function logException(Exception $e, SplFileInfo $file = null)
    {
        $message = 'Sales import fail: ' . $e->getMessage() . PHP_EOL;
        if ($file) {
            $message.= 'File: ' . $file . PHP_EOL;
        }
        $this->logRepository->createLog($message);
    }

    /**
     * @param string $dir
     * @throws \UnexpectedValueException
     * @return SplFileInfo[]|SortableDirectoryIterator
     */
    protected function getXmlFiles($dir)
    {
        $files = new SortableDirectoryIterator($dir);
        $files->sortByFilename(SortableDirectoryIterator::SORT_ASC);
        return $files;
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
