<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Type\AlcoholType;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

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
        $this->import($parser);

        $cursor = $productRepository->findAll();
        $this->assertCount(4, $cursor);

        $groupRepository = $this->getContainer()->get('lighthouse.core.document.repository.classifier.group');
        $groups = $groupRepository->findAll();
        $this->assertCount(3, $groups);

        $categoryRepository = $this->getContainer()->get('lighthouse.core.document.repository.classifier.category');
        $categories = $categoryRepository->findAll();
        $this->assertCount(4, $categories);

        $subCategoryRepository = $this->getContainer()
            ->get('lighthouse.core.document.repository.classifier.subcategory');
        $subCategories = $subCategoryRepository->findAll();
        $this->assertCount(4, $subCategories);
    }

    public function testImportProductTypes()
    {
        $this->clearMongoDb();

        /* @var ProductRepository $productRepository */
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');

        $parser = $this->createXmlParser();
        $this->import($parser);

        $this->assertEquals(4, $productRepository->count());

        $product1 = $productRepository->findOneBySku('46091758');
        $this->assertInstanceOf(Product::getClassName(), $product1);
        $this->assertInstanceOf(UnitType::getClassName(), $product1->typeProperties);
        $this->assertEquals(UnitType::TYPE, $product1->getType());

        $product2 = $productRepository->findOneBySku('4008713700510');
        $this->assertInstanceOf(Product::getClassName(), $product2);
        $this->assertInstanceOf(UnitType::getClassName(), $product2->typeProperties);
        $this->assertEquals(UnitType::TYPE, $product2->getType());

        $product3 = $productRepository->findOneBySku('2808650');
        $this->assertInstanceOf(Product::getClassName(), $product3);
        $this->assertInstanceOf(WeightType::getClassName(), $product3->typeProperties);
        $this->assertEquals(WeightType::TYPE, $product3->getType());
        $this->assertEquals('Шашлык из креветок пр-во Лэнд', $product3->typeProperties->nameOnScales);
        $this->assertEquals('Шашлык из креветок пр-во Лэнд', $product3->typeProperties->descriptionOnScales);
        $this->assertEquals('креветки,ананас конс.,перец свежий,сок лимона,', $product3->typeProperties->ingredients);
        $this->assertEquals('приправа,масло раст.,соль', $product3->typeProperties->nutritionFacts);
        $this->assertNull($product3->typeProperties->shelfLife);

        $product4 = $productRepository->findOneBySku('4100051250');
        $this->assertInstanceOf(Product::getClassName(), $product4);
        $this->assertInstanceOf(AlcoholType::getClassName(), $product4->typeProperties);
        $this->assertEquals(AlcoholType::TYPE, $product4->getType());
        $this->assertInstanceOf(Quantity::getClassName(), $product4->typeProperties->volume);
        $this->assertSame(
            '0.500',
            $product4->typeProperties->volume->toString()
        );
        $this->assertInstanceOf(Quantity::getClassName(), $product4->typeProperties->alcoholByVolume);
        $this->assertSame(
            '7.200',
            $product4->typeProperties->alcoholByVolume->toString()
        );
    }
}
