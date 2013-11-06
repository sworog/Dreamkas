<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\RemoteDirectory;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\SalesImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.import.set10_sales_import")
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
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * @DI\InjectParams({
     *      "importer" = @DI\Inject("lighthouse.core.integration.set10.import_cheques.importer"),
     *      "remoteDirectory" = @DI\Inject("lighthouse.core.integration.set10.import_sales.remote_directory"),
     *      "logRepository" = @DI\Inject("lighthouse.core.document.repository.log")
     * })
     * @param SalesImporter $importer
     * @param RemoteDirectory $remoteDirectory
     * @param LogRepository $logRepository
     */
    public function __construct(
        SalesImporter $importer,
        RemoteDirectory $remoteDirectory,
        LogRepository $logRepository
    ) {
        parent::__construct('lighthouse:import:sales');

        $this->importer = $importer;
        $this->remoteDirectory = $remoteDirectory;
        $this->logRepository = $logRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirUrl = null;
        try {
            $dirUrl = $this->remoteDirectory->getDirUrl();
            $output->write(sprintf('Reading remote directory "%s" ... ', $dirUrl));
            $files = $this->remoteDirectory->read();
            $output->writeln('Done');
        } catch (\Exception $e) {
            $this->logException($e, $dirUrl);
            throw $e;
        }

        foreach ($files as $file) {
            try {
                $output->writeln(sprintf('Importing "%s"', $file->getFilename()));
                $parser = new SalesXmlParser($file->getPathname());
                $this->importer->import($parser, $output);
                foreach ($this->importer->getErrors() as $error) {
                    $this->logException($error['exception'], $dirUrl, $file->getPathname());
                }
            } catch (\Exception $e) {
                $this->logException($e, $dirUrl, $file->getPathname());
                $output->writeln(sprintf('<error>Failed to import sales</error>: %s', $e->getMessage()));
            }
            $output->writeln('');
            $output->writeln(sprintf('Deleting "%s" ... ', $file->getFilename()));
            $this->remoteDirectory->deleteFile($file);
            $output->writeln('Done');
        }

        $output->writeln('Finished importing');
    }

    /**
     * @param \Exception $e
     * @param string $url
     * @param string $file
     */
    protected function logException(\Exception $e, $url = null, $file = null)
    {
        $message = 'Sales import fail: ' . $e->getMessage() . PHP_EOL;
        if ($url) {
            $message.= 'Url: ' . $url . PHP_EOL;
        }
        if ($file) {
            $message.= 'File: ' . $file . PHP_EOL;
        }
        $this->logRepository->createLog($message);
    }
}
