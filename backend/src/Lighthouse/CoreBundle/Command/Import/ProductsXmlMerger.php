<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Util\File\SortableDirectoryIterator;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XMLReader;
use SplFileInfo;
use SplFileObject;
use LibXMLError;

/**
 * @DI\Service("lighthouse.core.command.import.products_xml_merger")
 * @DI\Tag("console.command")
 */
class ProductsXmlMerger extends Command
{
    /**
     * @var array
     */
    protected $skus;

    /**
     * @var SplFileObject
     */
    protected $mergeFile;

    /**
     * @var DotHelper
     */
    protected $dotHelper;

    /**
     * @var LibXMLError[]
     */
    protected $errors = array();

    public function configure()
    {
        $this->setName('lighthouse:import:products:xml_merger');
        $this->addArgument('dir', InputArgument::REQUIRED, 'Directory with xml files');
        $this->addArgument('save', InputArgument::REQUIRED, 'Resulting file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dotHelper = new DotHelper($output);

        $dir = $input->getArgument('dir');
        $saveFile = $input->getArgument('save');

        $this->createMergeFile($saveFile);

        $files = $this->getXmlFiles($dir);
        $filesCount = count($files);
        foreach ($files as $i => $file) {
            $output->writeln('');
            $pos = $i + 1;
            $percent = ($pos / $filesCount) * 100;
            $output->writeln(
                sprintf(
                    'Processing %s %d of %d %5.1f%%',
                    $file->getFilename(),
                    $pos,
                    $filesCount,
                    $percent
                )
            );
            $this->readXmlFile($file, $output);
        }

        $output->writeln('');
        $output->writeln(sprintf('<info>Saving %s</info>', $this->mergeFile->getPathname()));
        $this->closeMergeFile();

        $this->outputErrors($output);
        $this->outputStats($output);
    }

    /**
     * @param string $mergeFile
     * @return SplFileObject
     */
    protected function createMergeFile($mergeFile)
    {
        $this->mergeFile = new SplFileObject($mergeFile, 'w');
        $this->mergeFile->fwrite('<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
        $this->mergeFile->fwrite('<goods-catalog>' . PHP_EOL);

        return $this->mergeFile;
    }

    protected function closeMergeFile()
    {
        $this->mergeFile->fwrite('</goods-catalog>');
    }

    /**
     * @param SplFileInfo $xmlFile
     */
    protected function readXmlFile(SplFileInfo $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->open($xmlFile->getPathname(), 'UTF-8');
        while (@$xmlReader->read()) {
            if (XMLReader::ELEMENT === $xmlReader->nodeType && 'good' == $xmlReader->name) {
                $sku = $xmlReader->getAttribute('marking-of-the-good');
                if (!isset($this->skus[$sku])) {
                    $nodeXml = @$xmlReader->readOuterXml();
                    if ('' == $nodeXml) {
                        $this->dotHelper->writeError('E');
                        $this->errors[] = libxml_get_last_error();
                        break;
                    } else {
                        $this->mergeFile->fwrite($nodeXml . PHP_EOL);
                        $this->skus[$sku] = 1;
                        $this->dotHelper->writeInfo('.');
                    }
                } else {
                    $this->skus[$sku]++;
                    $this->dotHelper->writeComment('S');
                }
            }
        }
        $this->dotHelper->end();
    }

    /**
     * @param string $dir
     * @throws \UnexpectedValueException
     * @return SplFileInfo[]|SortableDirectoryIterator
     */
    protected function getXmlFiles($dir)
    {
        $files = new SortableDirectoryIterator($dir);
        if (!$files->getFileInfo()->isDir()) {
            throw new \UnexpectedValueException(sprintf('Path "%s" is not directory', $dir));
        }
        $files->sortByTime(SortableDirectoryIterator::SORT_DESC);
        return $files;
    }

    /**
     * @param OutputInterface $output
     */
    protected function outputErrors(OutputInterface $output)
    {
        foreach ($this->errors as $error) {
            $output->writeln(
                sprintf(
                    '<error>%s: %s at %s:%s</error>',
                    $error->file,
                    trim($error->message),
                    $error->line,
                    $error->column
                )
            );
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function outputStats(OutputInterface $output)
    {
        $total = array_sum($this->skus);
        $unique = count($this->skus);
        $average = $total / $unique;
        $max = max($this->skus);

        $output->writeln('');
        /* @var TableHelper $tableHelper */
        $tableHelper = $this->getHelper('table');
        $tableHelper->setHeaders(array('Stats'));
        $tableHelper->addRow(array('Unique', $unique));
        $tableHelper->addRow(array('Total', $total));
        $tableHelper->addRow(array('Average', sprintf('%.1f', $average)));
        $tableHelper->addRow(array('Max', $max));
        $tableHelper->render($output);
    }
}
