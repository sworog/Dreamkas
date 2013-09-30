<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
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
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var int
     */
    protected $batchSize = 1;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @DI\InjectParams({
     *      "dm" = @DI\Inject("doctrine_mongodb.odm.document_manager"),
     *      "validator" = @DI\Inject("validator")
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
        $this->count = 0;
        $batchSize = ($batchSize) ?: $this->batchSize;

        while ($product = $parser->createNextProduct()) {
            try {
                $this->validate($product);
                $this->dm->persist($product);
                $output->writeln(sprintf('Persist product "%s"', $product->name));
                if (0 == ++$this->count % $batchSize) {
                    $this->dm->flush();
                    $output->writeln('Flushing');
                }
            } catch (ValidationFailedException $e) {
                $output->writeln($e->getMessage());
            }
        }
        $this->dm->flush();
    }

    /**
     * @param Product $product
     * @throws ValidationFailedException
     */
    protected function validate(Product $product)
    {
        $constraintViolationList = $this->validator->validate($product);
        if ($constraintViolationList->count() > 0) {
            throw new ValidationFailedException($constraintViolationList);
        }
    }
}
