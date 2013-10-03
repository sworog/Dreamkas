<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Integration\Set10\Import\GoodElement;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class Set10ProductImportXmlParserTest extends ContainerAwareTestCase
{
    /**
     * @param string $xmlFilePath
     * @return Set10ProductImportXmlParser
     */
    protected function createXmlParser($xmlFilePath = null)
    {
        $xmlFilePath = ($xmlFilePath) ?: __DIR__ . '/../../../Fixtures/Integration/Set10/Import/goods.xml';
        return new Set10ProductImportXmlParser($xmlFilePath);
    }

    public function testReadNextNodeReturnsSimpleXml()
    {
        $parser = $this->createXmlParser();

        $simpleXml = $parser->readNextNode();
        $this->assertInstanceOf('\SimpleXmlElement', $simpleXml);
        $this->assertEquals('good', $simpleXml->getName());
        $this->assertNotNull($simpleXml->name);
        $this->assertNotNull($simpleXml->group);
    }

    public function testGroupsParsing()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextNode();
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
        $parser = $this->createXmlParser();

        $good = $parser->readNextNode();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $parser->readNextNode();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $parser->readNextNode();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $parser->readNextNode();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $parser->readNextNode();
        $this->assertFalse($good);
    }

    public function testOnlyGroupNodesAreRead()
    {
        $parser = $this->createXmlParser();
        $groupNodesCount = 0;
        while ($good = $parser->readNextNode()) {
            $this->assertInstanceOf(GoodElement::getClassName(), $good);
            $this->assertEquals('good', $good->getName());
            $groupNodesCount++;
        }
        $this->assertEquals(4, $groupNodesCount);
    }

    public function testCreateNextProduct()
    {
        $parser = $this->createXmlParser();

        while ($product = $parser->createNextProduct()) {
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
        $parser = $this->createXmlParser();

        $product1 = $parser->createNextProduct();
        $this->assertEquals('Жевательные резинки, конфеты', $product1->subCategory->name);
        $this->assertEquals('Кондитерские изделия', $product1->subCategory->category->name);
        $this->assertEquals('Бакалейный отдел', $product1->subCategory->category->group->name);

        $product2 = $parser->createNextProduct();
        $this->assertEquals('Мюсли, каши, хлопья', $product2->subCategory->name);
        $this->assertEquals('Крупы, каши, макаронные изделия', $product2->subCategory->category->name);
        $this->assertEquals('Бакалейный отдел', $product2->subCategory->category->group->name);
        $this->assertSame($product2->subCategory->category->group, $product1->subCategory->category->group);

        $product3 = $parser->createNextProduct();
        $this->assertEquals('Рыбные Блюда,Морепродукты', $product3->subCategory->name);
        $this->assertEquals('Кулинария ЛЭНД', $product3->subCategory->category->name);
        $this->assertEquals('Производство ЛЭНД', $product3->subCategory->category->group->name);

        $product4 = $parser->createNextProduct();
        $this->assertEquals('Блюда из птицы', $product4->subCategory->name);
        $this->assertEquals('Кулинария ЛЭНД', $product4->subCategory->category->name);
        $this->assertEquals('Производство ЛЭНД', $product4->subCategory->category->group->name);
        $this->assertSame($product4->subCategory->category, $product3->subCategory->category);
        $this->assertSame($product4->subCategory->category->group, $product3->subCategory->category->group);

        $this->assertFalse($parser->createNextProduct());
    }

    public function testPriceGenerate()
    {
        $parser = $this->createXmlParser();

        $product1 = $parser->createNextProduct();
        $this->assertEquals(4000, $product1->purchasePrice->getCount());
        $this->assertEquals(15, $product1->retailMarkupMin);
        $this->assertEquals(40, $product1->retailMarkupMax);
        $this->assertEquals($product1::RETAIL_PRICE_PREFERENCE_MARKUP, $product1->retailPricePreference);

        $product2 = $parser->createNextProduct();
        $this->assertEquals(7520, $product2->purchasePrice->getCount());
        $this->assertEquals(15, $product2->retailMarkupMin);
        $this->assertEquals(40, $product2->retailMarkupMax);
        $this->assertEquals($product2::RETAIL_PRICE_PREFERENCE_MARKUP, $product2->retailPricePreference);

        $product3 = $parser->createNextProduct();
        $this->assertEquals(88000, $product3->purchasePrice->getCount());
        $this->assertEquals(15, $product3->retailMarkupMin);
        $this->assertEquals(40, $product3->retailMarkupMax);
        $this->assertEquals($product3::RETAIL_PRICE_PREFERENCE_MARKUP, $product3->retailPricePreference);

        $product4 = $parser->createNextProduct();
        $this->assertEquals(35200, $product4->purchasePrice->getCount());
        $this->assertEquals(15, $product4->retailMarkupMin);
        $this->assertEquals(40, $product4->retailMarkupMax);
        $this->assertEquals($product4::RETAIL_PRICE_PREFERENCE_MARKUP, $product4->retailPricePreference);
    }
}
