<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Clamidity\ProfilerBundle\DataCollector\XhprofCollector;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\PurchaseElement;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Util\File\SortableDirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Exception;
use SplFileInfo;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

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
     * @var XhprofCollector
     */
    protected $profiler;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import.sales.importer"),
     *      "logRepository" = @DI\Inject("lighthouse.core.document.repository.log"),
     *      "profiler" = @DI\Inject("data_collector.xhprof"),
     * })
     * @param SalesImporter $importer
     * @param LogRepository $logRepository
     * @param XhprofCollector $profiler
     */
    public function __construct(
        SalesImporter $importer,
        LogRepository $logRepository,
        XhprofCollector $profiler
    ) {
        parent::__construct();

        $this->importer = $importer;
        $this->logRepository = $logRepository;
        $this->profiler = $profiler;
    }

    protected function configure()
    {
        $this
            ->setName('lighthouse:import:sales:local')
            ->setDescription('Import local Set10 purchases xml')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to xml file or directory')
            ->addOption('batch-size', null, InputOption::VALUE_OPTIONAL, 'Batch size', 1000)
            ->addOption('import-date', null, InputOption::VALUE_OPTIONAL, 'Date to import to')
            ->addOption('receipt-date', null, InputOption::VALUE_OPTIONAL, 'Date of first receipt')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'End date')
            ->addOption('profile', null, InputOption::VALUE_NONE, 'Save xhprof profile data')
            ->addOption('sort', null, InputOption::VALUE_OPTIONAL, 'Sort files by: filename, filedate', 'filename')
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
        $importDate = $input->getOption('import-date');
        $receiptDate = $input->getOption('receipt-date');
        $dryRun = $input->getOption('dry-run');
        $profile = $input->getOption('profile');
        $sort = $input->getOption('sort');

        $datePeriod = $this->getDatePeriod($importDate, $receiptDate);
        $files = $this->getXmlFiles($filePath, $sort);
        $filesCount = count($files);

        $output->writeln(sprintf('Found %d files', $filesCount));

        if ($profile) {
            $this->profiler->startProfiling($this->getName());
        }

        $stopwatch = new Stopwatch();
        $dotHelper = new DotHelper($output);
        foreach ($files as $file) {
            if ($dryRun) {
                $this->checkDates($file, $output, $datePeriod);
            } else {
                $this->importFile($file, $output, $batchSize, $datePeriod, $stopwatch, $dotHelper);
            }
        }

        $output->writeln('');
        $output->writeln('Finished importing');

        if ($profile) {
            $runId = $this->profiler->stopProfiling();
            $output->writeln(sprintf('Run: %s', $runId));
        }

        if (!$dryRun) {
            $this->showStats($stopwatch, $output);
        }
    }

    /**
     * @param Stopwatch $stopwatch
     * @param OutputInterface $output
     */
    protected function showStats(Stopwatch $stopwatch, OutputInterface $output)
    {
        $events = $stopwatch->getSectionEvents('__root__');
        if (!isset($events['all'], $events['position'])) {
            return;
        }

        /* @var TableHelper $tableHelper */
        $tableHelper = $this->getHelper('table');
        $tableHelper->setPadType(STR_PAD_LEFT);
        $tableHelper->setHeaders(array('', 'count', 'ms', 'pos/ms', 'ms/pos', '%'));

        $tableHelper->addRow($this->getEventStats('Parse', $events, 'parse'));
        $tableHelper->addRow($this->getEventStats('Pos', $events, 'position'));
        $tableHelper->addRow($this->getEventStats('Receipt', $events, 'receipt'));
        $tableHelper->addRow($this->getEventStats('Persist', $events, 'persist'));
        $tableHelper->addRow($this->getEventStats('Flush', $events, 'flush'));
        $tableHelper->addRow($this->getEventStats('All', $events, 'all'));

        $tableHelper->render($output);
    }

    /**
     * @param string $title
     * @param StopwatchEvent[] $events
     * @param string $key
     * @return array
     */
    protected function getEventStats(
        $title,
        array $events,
        $key
    ) {
        $duration = (isset($events[$key])) ? $events[$key]->getDuration() : 0;
        $count = (isset($events[$key])) ? count($events[$key]->getPeriods()) : 0;
        return array(
            $title,
            sprintf('%d', $duration),
            sprintf('%d', $count),
            sprintf('%.02f', $this->div(count($events['position']->getPeriods()), $duration)),
            sprintf('%.03f', $this->div($duration, count($events['position']->getPeriods()))),
            sprintf('%.02f', $this->div($duration, $events['all']->getDuration()) * 100)
        );
    }

    /**
     * @param float|int $a
     * @param float|int $b
     * @return float|int
     */
    protected function div($a, $b)
    {
        return (0 == $b) ? 0 : $a / $b;
    }

    /**
     * @param SplFileInfo $file
     * @param OutputInterface $output
     * @param int $batchSize
     * @param DatePeriod $datePeriod
     * @param Stopwatch $stopwatch
     * @param DotHelper $dotHelper
     */
    protected function importFile(
        SplFileInfo $file,
        OutputInterface $output,
        $batchSize = null,
        DatePeriod $datePeriod = null,
        Stopwatch $stopwatch = null,
        DotHelper $dotHelper = null
    ) {
        try {
            $output->writeln('');
            $output->writeln(sprintf('Importing "%s"', $file->getFilename()));
            $parser = new SalesXmlParser($file->getPathname());
            $this->importer->import($parser, $output, $batchSize, $datePeriod, $dotHelper, $stopwatch);

            foreach ($this->importer->getErrors() as $error) {
                /* @var Exception $exception */
                list(, $exception) = $error;
                $this->logException($exception, $file);
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
            /* @var PurchaseElement $purchaseElement */
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
     * @param string $sort
     * @return SplFileInfo[]|SortableDirectoryIterator
     */
    protected function getXmlFiles($dir, $sort)
    {
        $files = new SortableDirectoryIterator($dir);
        switch ($sort) {
            case 'filedate':
                $files->filterPurchaseFiles();
                $files->sortByDateFilename(SortableDirectoryIterator::SORT_ASC);
                break;
            case 'filename':
            default:
                $files->sortByFilename(SortableDirectoryIterator::SORT_ASC);
        }
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
