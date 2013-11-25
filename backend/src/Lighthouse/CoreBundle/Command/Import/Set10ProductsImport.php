<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\RemoteDirectory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

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
     * @var Set10ProductImportXmlParser
     */
    protected $parser;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import.products.importer")
     * })
     * @param Set10ProductImporter $importer
     */
    public function setImporterProvider(Set10ProductImporter $importer)
    {
        $this->importer = $importer;
    }

    /**
     * @DI\InjectParams({
     *      "parser" = @DI\Inject("lighthouse.core.integration.set10.import.products.xml_parser")
     * })
     * @param Set10ProductImportXmlParser $parser
     */
    public function setParserProvider(Set10ProductImportXmlParser $parser)
    {
        $this->parser = $parser;
    }

    protected function configure()
    {
        $this
            ->setName('lighthouse:import:products')
            ->setDescription('Import product catalog from Set10')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to xml file')
            ->addArgument('batch-size', InputArgument::OPTIONAL, 'Batch size', 1000)
            ->addOption('update', null, InputOption::VALUE_NONE, 'Update existing products');
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

        $this->parser->setXmlFilePath($filePath);

        $this->importer->import($this->parser, $output, $batchSize, $update);

        $endTime = time();

        $output->writeln('Done');
        $execTime = $endTime - $startTime;
        $output->writeln('Executed time: '. $execTime . ' seconds');

        return 0;
    }
}
