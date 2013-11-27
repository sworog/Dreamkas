<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Exception\InvalidArgumentException;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImportXmlParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use FilesystemIterator;
use SplFileInfo;

/**
 * @DI\Service("lighthouse.core.command.import.set10_products_import")
 * @DI\Tag("console.command")
 */
class Set10ProductsImport extends Command
{
    /**
     * @var Set10ProductImporter
     */
    protected $importer;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import.products.importer")
     * })
     * @param Set10ProductImporter $importer
     */
    public function __construct(Set10ProductImporter $importer)
    {
        parent::__construct();

        $this->importer = $importer;
    }

    protected function configure()
    {
        $this
            ->setName('lighthouse:import:products')
            ->setDescription('Import product catalog from Set10')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to xml file')
            ->addArgument('batch-size', InputArgument::OPTIONAL, 'Batch size', 1000)
            ->addOption('update', null, InputOption::VALUE_NONE, 'Update existing products')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn("Starting import\n");

        $startTime = time();

        $filePath = $input->getArgument('file');
        $batchSize = $input->getArgument('batch-size');
        $update = $input->getOption('update');

        $files = $this->getFilesList($filePath);
        $filesCount = count($files);
        foreach ($files as $i => $file) {
            $output->writeln('');
            $output->writeln(sprintf('Importing %s %d of %d', $file->getFilename(), $i + 1, $filesCount));
            $output->writeln('');
            $parser = new Set10ProductImportXmlParser($file->getPathname());
            try {
                $this->importer->import($parser, $output, $batchSize, $update);
            } catch (RuntimeException $e) {
                $output->writeln('Error: ' . $e->getMessage());
            }
        }

        $endTime = time();

        $output->writeln('Done');
        $execTime = $endTime - $startTime;
        $output->writeln('Executed time: '. $execTime . ' seconds');

        return 0;
    }

    /**
     * @param string $filePath
     * @return \SplFileInfo[]
     * @throws \InvalidArgumentException
     */
    protected function getFilesList($filePath)
    {
        $file = new SplFileInfo($filePath);
        $files = array();
        if ($file->isFile()) {
            $files[] = $file;
        } elseif ($file->isDir()) {
            $dir = new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS);
            /* @var SplFileInfo $file*/
            foreach ($dir as $file) {
                if ($file->isFile()) {
                    $files[] = $file;
                }
            }
        } else {
            throw new InvalidArgumentException(sprintf('Path %s is not file or dir', $filePath));
        }
        usort($files, function (SplFileInfo $a, SplFileInfo $b) {
            return strcmp($b->getFilename(), $a->getFilename());
        });
        return $files;
    }
}
