<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Integration\Set10\ImportSales\ImportSalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\RemoteDirectory;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\SalesImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.integration.sales_import")
 * @DI\Tag("console.command")
 */
class Set10SalesImport extends Command
{
    /**
     * @var SalesImporter
     */
    protected $importer;

    /**
     * @var RemoteDirectory
     */
    protected $remoteDirectory;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import_sales.importer"),
     *      "remoteDirectory" = @DI\Inject("lighthouse.core.integration.set10.import_sales.remote_directory"),
     * })
     * @param SalesImporter $importer
     * @param RemoteDirectory $remoteDirectory
     */
    public function __construct(SalesImporter $importer, RemoteDirectory $remoteDirectory)
    {
        parent::__construct();

        $this->importer = $importer;
        $this->remoteDirectory = $remoteDirectory;
    }

    protected function configure()
    {
        $this->setName('lighthouse:import:sales');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(sprintf('Reading remote directory "%s" ... ', $this->remoteDirectory->getDirUrl()));
        $files = $this->remoteDirectory->read();
        $output->writeln('Done');

        foreach ($files as $file) {
            try {
                $output->writeln(sprintf('Importing "%s"', $file->getFilename()));
                $parser = new ImportSalesXmlParser($file->getPathname());
                $this->importer->import($parser, $output);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<error>Failed to import sales</error>: %s', $e->getMessage()));
            }
            $output->writeln('');
            $output->writeln(sprintf('Deleting "%s" ... ', $file->getFilename()));
            $this->remoteDirectory->deleteFile($file);
            $output->writeln('Done');
        }

        $output->writeln('Finished importing');
    }
}
