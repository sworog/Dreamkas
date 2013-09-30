<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Integration\Set10\Import\GoodElement;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImporter;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class Set10ProductImporterTest extends ContainerAwareTestCase
{
    /**
     * @param string $xmlFilePath
     * @return Set10ProductImporter
     */
    protected function createImporter($xmlFilePath = null)
    {
        $xmlFilePath = ($xmlFilePath) ?: '../../../Fixtures/Integration/Set10/Import/goods.xml';
        $importer = new Set10ProductImporter($xmlFilePath);
        return $importer;
    }

    public function testReadNextNodeReturnsSimpleXml()
    {
        $importer = $this->createImporter();

        $simpleXml = $importer->readNextNode();
        $this->assertInstanceOf('\SimpleXmlElement', $simpleXml);
        $this->assertEquals('good', $simpleXml->getName());
        $this->assertNotNull($simpleXml->name);
        $this->assertNotNull($simpleXml->group);
    }

    public function testGroupsParsing()
    {
        $importer = $this->createImporter();

        $good = $importer->readNextNode();
        $this->assertInstanceOf(GoodElement::getClassName(), $good);

        $groups = $good->getGroups();
        $this->assertInternalType('array', $groups);
        $this->assertCount(3, $groups);
        $this->assertEquals('@1000', $groups[0]['id']);
        $this->assertEquals('Бакалейный отдел', $groups[0]['name']);
        $this->assertEquals('60627', $groups[1]['id']);
        $this->assertEquals('Кондитерские изделия', $groups[1]['name']);
        $this->assertEquals('@218', $groups[2]['id']);
        $this->assertEquals('Жевательные резинки, конфеты', $groups[2]['name']);
    }

    public function testMeasurementParsing()
    {
        $importer = $this->createImporter();

        $good = $importer->readNextNode();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $importer->readNextNode();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $importer->readNextNode();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $importer->readNextNode();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $importer->readNextNode();
        $this->assertFalse($good);
    }

    public function testOnlyGroupNodesAreRead()
    {
        $importer = $this->createImporter();
        $groupNodesCount = 0;
        while ($good = $importer->readNextNode()) {
            $this->assertInstanceOf(GoodElement::getClassName(), $good);
            $this->assertEquals('good', $good->getName());
            $groupNodesCount++;
        }
        $this->assertEquals(4, $groupNodesCount);
    }

    public function testCreateNextProduct()
    {
        $importer = $this->createImporter();

        while ($product = $importer->createNextProduct()) {
            $this->assertInstanceOf(Product::getClassName(), $product);
            $this->assertNotNull($product->name);
            $this->assertNotNull($product->sku);
            $this->assertNotNull($product->barcode);
            $this->assertNotNull($product->units);
            $this->assertNotNull($product->vat);
            $this->assertNotNull($product->vendor);
            $this->assertNotNull($product->subCategory);
        }
    }

    public function testClassifier()
    {
        $importer = $this->createImporter();

        $product1 = $importer->createNextProduct();
        $this->assertEquals('Жевательные резинки, конфеты', $product1->subCategory->name);
        $this->assertEquals('Кондитерские изделия', $product1->subCategory->category->name);
        $this->assertEquals('Бакалейный отдел', $product1->subCategory->category->group->name);

        $product2 = $importer->createNextProduct();
        $this->assertEquals('Мюсли, каши, хлопья', $product2->subCategory->name);
        $this->assertEquals('Крупы, каши, макаронные изделия', $product2->subCategory->category->name);
        $this->assertEquals('Бакалейный отдел', $product2->subCategory->category->group->name);
        $this->assertSame($product2->subCategory->category->group, $product1->subCategory->category->group);

        $product3 = $importer->createNextProduct();
        $this->assertEquals('Рыбные Блюда,Морепродукты', $product3->subCategory->name);
        $this->assertEquals('Кулинария ЛЭНД', $product3->subCategory->category->name);
        $this->assertEquals('Производство ЛЭНД', $product3->subCategory->category->group->name);

        $product4 = $importer->createNextProduct();
        $this->assertEquals('Блюда из птицы', $product4->subCategory->name);
        $this->assertEquals('Кулинария ЛЭНД', $product4->subCategory->category->name);
        $this->assertEquals('Производство ЛЭНД', $product4->subCategory->category->group->name);
        $this->assertSame($product4->subCategory->category, $product3->subCategory->category);
        $this->assertSame($product4->subCategory->category->group, $product3->subCategory->category->group);

        $this->assertFalse($importer->createNextProduct());
    }
}
