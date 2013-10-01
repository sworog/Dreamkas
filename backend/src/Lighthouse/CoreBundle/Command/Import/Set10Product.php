<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImportXmlParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.import.set10_product")
 * @DI\Tag("console.command")
 */
class Set10Product extends Command
{
    /**
     * @var Set10ProductImporter
     */
    protected $importer;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.importer")
     * })
     * @param Set10ProductImporter $importer
     */
    public function setUserProvider(Set10ProductImporter $importer)
    {
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this
            ->setName('lighthouse:import:products')
            ->setDescription('Import product catalog from Set10')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to xml file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn("Starting import\n");

        $filePath = $input->getArgument('file');

        $parser = new Set10ProductImportXmlParser($filePath);

        $this->importer->import($parser, $output);

        $output->writeln('Done');

        return 0;
    }
}
