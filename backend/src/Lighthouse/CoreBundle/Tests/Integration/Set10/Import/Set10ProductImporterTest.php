<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import;

use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Output\NullOutput;

class Set10ProductImporterTest extends ContainerAwareTestCase
{
    /**
     * @param string $xmlFilePath
     * @return Set10ProductImportXmlParser
     */
    protected function createXmlParser($xmlFilePath = 'Integration/Set10/Import/goods.xml')
    {
        $xmlFilePath = $this->getFixtureFilePath($xmlFilePath);
        $parser = $this->getContainer()->get('lighthouse.core.integration.set10.product_xml_parser');
        $parser->setXmlFilePath($xmlFilePath);
        return $parser;
    }

    public function testImport()
    {
        $this->clearMongoDb();

        /* @var ProductRepository $productRepository */
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');
        $cursor = $productRepository->findAll();
        $this->assertCount(0, $cursor);

        $parser = $this->createXmlParser();
        /* @var Set10ProductImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.importer');
        $output = new NullOutput();
        $importer->import($parser, $output);

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
