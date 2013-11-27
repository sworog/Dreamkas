<?php

namespace Lighthouse\CoreBundle\Command\Import;

use Lighthouse\CoreBundle\Exception\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XMLReader;
use SplFileInfo;
use SplFileObject;
use FilesystemIterator;
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
     * @var int
     */
    protected $dotsPos = 0;

    /**
     * @var int
     */
    protected $dotsLength = 50;

    /**
     * @var OutputInterface
     */
    protected $output;

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
        $dir = $input->getArgument('dir');
        $saveFile = $input->getArgument('save');

        $this->output = $output;

        $this->createMergeFile($saveFile);

        $files = $this->getXmlFiles($dir);
        $filesCount = count($files);
        foreach ($files as $i => $file) {
            $output->writeln('');
            $output->writeln(
                sprintf(
                    'Processing %s %d of %d %5.1f%%',
                    $file->getFilename(),
                    $i + 1,
                    $filesCount,
                    ($i + 1) / $filesCount * 100
                )
            );
            $this->readXmlFile($file, $output);
        }

        $output->writeln('');
        $output->writeln(sprintf('<info>Saving %s</info>', $this->mergeFile->getPathname()));
        $this->closeMergeFile();

        $this->outputErrors();
        $this->outputStats();
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
                        $this->writeDot('<error>E</error>');
                        $this->errors[] = libxml_get_last_error();
                        break;
                    } else {
                        $this->mergeFile->fwrite($nodeXml . PHP_EOL);
                        $this->skus[$sku] = 1;
                        $this->writeDot('<info>.</info>');
                    }
                } else {
                    $this->skus[$sku]++;
                    $this->writeDot('<comment>S</comment>');
                }
            }
        }
        $this->resetDots();
    }

    /**
     * @param string $dir
     * @throws InvalidArgumentException
     * @return SplFileInfo[]
     */
    protected function getXmlFiles($dir)
    {
        $dirInfo = new SplFileInfo($dir);
        $files = array();
        if ($dirInfo->isDir()) {
            $folderIterator = new FilesystemIterator($dirInfo->getPathname(), FilesystemIterator::SKIP_DOTS);
            /* @var \SplFileInfo $file*/
            foreach ($folderIterator as $file) {
                if ($file->isFile()) {
                    $files[] = $file;
                }
            }
        } else {
            throw new InvalidArgumentException(sprintf('%s is not a folder', $dir));
        }
        usort($files, function (SplFileInfo $a, SplFileInfo $b) {
            return $b->getMTime() - $a->getMTime();
        });
        return $files;
    }

    /**
     * @param string $dot
     */
    protected function writeDot($dot)
    {
        $this->output->write($dot);
        if (0 == ++$this->dotsPos % $this->dotsLength) {
            $this->output->writeln('   ' . $this->dotsPos);
        }
    }

    protected function resetDots()
    {
        $missingDots = $this->dotsLength - ($this->dotsPos % $this->dotsLength);
        $this->output->writeln(str_repeat(' ', $missingDots) . '   ' . $this->dotsPos);
        $this->dotsPos = 0;
    }

    protected function outputErrors()
    {
        foreach ($this->errors as $error) {
            $this->output->writeln(
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

    protected function outputStats()
    {
        $total = array_sum($this->skus);
        $unique = count($this->skus);
        $average = $total / $unique;
        $max = max($this->skus);

        $this->output->writeln('');
        /* @var TableHelper $tableHelper */
        $tableHelper = $this->getHelper('table');
        $tableHelper->setHeaders(array('Stats'));
        $tableHelper->addRow(array('Unique', $unique));
        $tableHelper->addRow(array('Total', $total));
        $tableHelper->addRow(array('Average', sprintf('%.1f', $average)));
        $tableHelper->addRow(array('Max', $max));
        $tableHelper->render($this->output);
    }
}
