<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
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
            ->addOption('batch-size', null, InputOption::VALUE_OPTIONAL, 'Batch size', 1000);
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

        $files = $this->getXmlFiles($filePath);
        $filesCount = count($files);
        $output->writeln(sprintf('Found %d files', $filesCount));
        foreach ($files as $file) {
            try {
                $output->writeln(sprintf('Importing "%s"', $file->getFilename()));
                $parser = new SalesXmlParser($file->getPathname());
                $this->importer->import($parser, $output, $batchSize);
                foreach ($this->importer->getErrors() as $error) {
                    $this->logException($error['exception'], $file);
                }
            } catch (Exception $e) {
                $this->logException($e, $file);
                $output->writeln(sprintf('<error>Failed to import sales</error>: %s', $e->getMessage()));
            }
        }

        $output->writeln('');
        $output->writeln('Finished importing');
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
}
