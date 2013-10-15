<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * @DI\Service("lighthouse.core.integration.set10.importer")
 */
class Set10ProductImporter
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @var int
     */
    protected $batchSize = 1;

    /**
     * @var int
     */
    protected $lineWidth = 50;

    /**
     * @DI\InjectParams({
     *      "dm" = @DI\Inject("doctrine_mongodb.odm.document_manager"),
     *      "validator" = @DI\Inject("lighthouse.core.validator")
     * })
     * @param ObjectManager $dm,
     * @param ValidatorInterface $validator
     */
    public function __construct(ObjectManager $dm, ValidatorInterface $validator)
    {
        $this->dm = $dm;
        $this->validator = $validator;
    }

    /**
     * @param Set10ProductImportXmlParser $parser
     * @param OutputInterface $output
     * @param int $batchSize
     */
    public function import(Set10ProductImportXmlParser $parser, OutputInterface $output, $batchSize = null)
    {
        $errors = array();
        $memStart = memory_get_usage();
        $count = 0;
        $batchSize = ($batchSize) ?: $this->batchSize;
        $verbose = $output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL;
        $totalStartTime = microtime(true);
        $startItemTime = microtime(true);
        $flushStartTime = microtime(true);
        $flushCount = 0;
        $lineCount = 0;
        while ($product = $parser->createNextProduct()) {
            $count++;
            $flushCount++;
            $lineCount++;
            try {
                $this->validator->validate($product);
                $this->dm->persist($product);
                if ($verbose) {
                    $output->writeln(sprintf('<info>Persist product "%s"</info>', $product->name));
                } else {
                    $output->write('.');
                }
            } catch (ValidationFailedException $e) {
                $errors[] = array(
                    'exception' => $e,
                    'product' => $product,
                );
                if ($verbose) {
                    $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
                } else {
                    $output->write('<error>E</error>');
                }
            }

            $stopItemTime = microtime(true);
            $itemTime = $stopItemTime - $startItemTime;
            if ($verbose) {
                $output->writeln(sprintf('<comment>Item time: %.02f ms</comment>', $itemTime * 1000));
            }
            $startItemTime = microtime(true);

            if ($this->lineWidth == $lineCount) {
                $output->writeln(sprintf('   %s', $count));
                $lineCount = 0;
            }

            if (0 == $count % $batchSize) {
                if (0 != $lineCount) {
                    $output->writeln('');
                }
                $output->write('<info>Flushing</info>');
                $this->dm->flush();
                $flushTime = microtime(true) - $flushStartTime;
                if ($verbose) {
                    $output->writeln('<info>Flushing</info>');
                } else {
                    $output->writeln(sprintf(' - %.02f prod/s', $flushCount / $flushTime));
                }
                $flushStartTime = microtime(true);
                $flushCount = 0;
                $lineCount = 0;
            }
        }
        $this->dm->flush();

        $totalTime = microtime(true) - $totalStartTime;
        $output->writeln('');
        $output->writeln(
            sprintf(
                '<info>Total</info> - %d products in %d seconds, %.02f prod/s',
                $count,
                $totalTime,
                $count / $totalTime
            )
        );
        $memStop = memory_get_usage();
        $output->writeln(
            sprintf(
                '<info>Memory usage</info> - start %dMB, end %dMB, diff %dMB, peak %dMB',
                $memStart / 1048576,
                $memStop / 1048576,
                ($memStop - $memStart)  / 1048576,
                memory_get_peak_usage() / 1048576
            )
        );
        if (count($errors) > 0) {
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(
                    sprintf(
                        '<comment>%s / %s</comment> - %s',
                        $error['product']->sku,
                        $error['product']->name,
                        $error['exception']->getMessage()
                    )
                );
            }
        }
    }
}
