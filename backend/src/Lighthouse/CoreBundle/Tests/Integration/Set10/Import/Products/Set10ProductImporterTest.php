<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Test\TestOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Set10ProductImporterTest extends ContainerAwareTestCase
{
    /**
     * @param string $xmlFilePath
     * @return Set10ProductImportXmlParser
     */
    protected function createXmlParser($xmlFilePath = 'Integration/Set10/Import/Products/goods.xml')
    {
        $xmlFilePath = $this->getFixtureFilePath($xmlFilePath);
        $parser = new Set10ProductImportXmlParser($xmlFilePath);
        return $parser;
    }

    /**
     * @param Set10ProductImportXmlParser $parser
     * @param int $batchSize
     * @param bool $update
     * @return TestOutput
     */
    public function import(
        Set10ProductImportXmlParser $parser,
        $batchSize = null,
        $update = false
    ) {
        /* @var Set10ProductImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import.products.importer');
        $output = new TestOutput();
        $importer->import($parser, $output, $batchSize, $update);
        return $output;
    }

    public function testImportOneFileImport()
    {
        $this->clearMongoDb();

        /* @var ProductRepository $productRepository */
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');
        $cursor = $productRepository->findAll();
        $this->assertCount(0, $cursor);

        $parser = $this->createXmlParser();
        $output = $this->import($parser);

        $cursor = $productRepository->findAll();
        $this->assertCount(4, $cursor);

        $groupRepository = $this->getContainer()->get('lighthouse.core.document.repository.classifier.group');
        $groups = $groupRepository->findAll();
        $this->assertCount(2, $groups);

        $categoryRepository = $this->getContainer()->get('lighthouse.core.document.repository.classifier.category');
        $categories = $categoryRepository->findAll();
        $this->assertCount(3, $categories);

        $subCategoryRepository = $this->getContainer()
            ->get('lighthouse.core.document.repository.classifier.subcategory');
        $subCategories = $subCategoryRepository->findAll();
        $this->assertCount(4, $subCategories);
    }
}
