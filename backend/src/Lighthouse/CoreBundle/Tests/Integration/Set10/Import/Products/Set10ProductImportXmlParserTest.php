<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\GoodElement;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImportXmlParser;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class Set10ProductImportXmlParserTest extends ContainerAwareTestCase
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

    public function testReadNextNodeReturnsSimpleXml()
    {
        $parser = $this->createXmlParser();

        $simpleXml = $parser->readNextElement();
        $this->assertInstanceOf('\SimpleXmlElement', $simpleXml);
        $this->assertEquals('good', $simpleXml->getName());
        $this->assertNotNull($simpleXml->name);
        $this->assertNotNull($simpleXml->group);
    }

    public function testGroupsParsing()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();
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

        $good = $parser->readNextElement();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $parser->readNextElement();
        $this->assertEquals(Product::UNITS_UNIT, $good->getUnits());

        $good = $parser->readNextElement();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $parser->readNextElement();
        $this->assertEquals(Product::UNITS_KG, $good->getUnits());

        $good = $parser->readNextElement();
        $this->assertFalse($good);
    }

    public function testMeasurementCaseSensitiveParsing()
    {
        $parser = $this->createXmlParser('Integration/Set10/Import/Products/goods-measurement.xml');

        $expected = array(
            Product::UNITS_UNIT,
            Product::UNITS_UNIT,
            Product::UNITS_UNIT,
            Product::UNITS_UNIT,
            Product::UNITS_KG,
            Product::UNITS_KG,
            Product::UNITS_KG,
            Product::UNITS_KG,
            null
        );

        foreach ($expected as $expectedUnit) {
            $good = $parser->readNextElement();
            $this->assertEquals($expectedUnit, $good->getUnits());
        }
    }

    public function testOnlyGroupNodesAreRead()
    {
        $parser = $this->createXmlParser();
        $groupNodesCount = 0;
        while ($good = $parser->readNextElement()) {
            $this->assertInstanceOf(GoodElement::getClassName(), $good);
            $this->assertEquals('good', $good->getName());
            $groupNodesCount++;
        }
        $this->assertEquals(4, $groupNodesCount);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Failed to parse node 'good': Extra content at the end of the document
     */
    public function testInvalidXml()
    {
        $parser = $this->createXmlParser('Integration/Set10/Import/Products/goods-invalid.xml');
        do {
            $good = $parser->readNextElement();
        } while ($good);
    }
}
