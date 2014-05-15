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

    public function testGroupsParsingWithDifferentGroupCount()
    {
        $parser = $this->createXmlParser('Integration/Set10/Import/Products/goods-groups.xml');

        /* @var GoodElement|false $element */
        $element = $parser->readNextElement();
        $groups = $element->getGroups();

        $this->assertCount(2, $groups);
        $this->assertEquals('Ц0000000454', $groups[0]['id']);
        $this->assertEquals('Безалкогольные напитки ', $groups[0]['name']);
        $this->assertEquals('Ц0000000530', $groups[1]['id']);
        $this->assertEquals('Соки', $groups[1]['name']);

        $element = $parser->readNextElement();
        $groups = $element->getGroups();
        $this->assertCount(1, $groups);
        $this->assertEquals('Ц0000000454', $groups[0]['id']);
        $this->assertEquals('Безалкогольные напитки ', $groups[0]['name']);

        $element = $parser->readNextElement();
        $groups = $element->getGroups();
        $this->assertCount(1, $groups);
        $this->assertEquals('Ц0000001506', $groups[0]['id']);
        $this->assertEquals('Сыры', $groups[0]['name']);

        $element = $parser->readNextElement();
        $groups = $element->getGroups();
        $this->assertCount(1, $groups);
        $this->assertEquals('Ц0000001919', $groups[0]['id']);
        $this->assertEquals('Красный ценник Водка', $groups[0]['name']);

        $element = $parser->readNextElement();
        $this->assertFalse($element);
    }

    public function testPluginPropertyParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('0.001', $good->getPluginProperty('precision'));

        $good = $parser->readNextElement();

        $this->assertEquals('0.001', $good->getPluginProperty('precision'));

        $good = $parser->readNextElement();

        $this->assertNotNull($good->getPluginProperty('composition'));
        $this->assertNotNull($good->getPluginProperty('storage-conditions'));
        $this->assertNotNull($good->getPluginProperty('food-value'));
        $this->assertNotNull($good->getPluginProperty('plu-number'));

        $this->assertNull($good->getPluginProperty('dummy'));
    }

    public function testProductTypeParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('ProductPieceEntity', $good->getProductType());

        $good = $parser->readNextElement();

        $this->assertEquals('ProductPieceEntity', $good->getProductType());

        $good = $parser->readNextElement();

        $this->assertEquals('ProductWeightEntity', $good->getProductType());

        $good = $parser->readNextElement();

        $this->assertEquals('ProductWeightEntity', $good->getProductType());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testGoodNameParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('Шар Чупа Чупс Смешарики Машина времени шокол.25г', $good->getGoodName());

        $good = $parser->readNextElement();

        $this->assertEquals('Шарики Брюгген Чокин Крипс рисовые шок.250г', $good->getGoodName());

        $good = $parser->readNextElement();

        $this->assertEquals('Шашлык из креветок пр-во Лэнд', $good->getGoodName());

        $good = $parser->readNextElement();

        $this->assertEquals('Шашлык из курицы (филе) п/ф Кулинария', $good->getGoodName());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testVatParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('18', $good->getVat());

        $good = $parser->readNextElement();

        $this->assertEquals('18', $good->getVat());

        $good = $parser->readNextElement();

        $this->assertEquals('18', $good->getVat());

        $good = $parser->readNextElement();

        $this->assertEquals('18', $good->getVat());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testMarkingOfTheGoodParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('46091758', $good->getMarkingOfTheGood());

        $good = $parser->readNextElement();

        $this->assertEquals('4008713700510', $good->getMarkingOfTheGood());

        $good = $parser->readNextElement();

        $this->assertEquals('2808650', $good->getMarkingOfTheGood());

        $good = $parser->readNextElement();

        $this->assertEquals('2809733', $good->getMarkingOfTheGood());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testBarcodeParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('46091758', $good->getBarcode());

        $good = $parser->readNextElement();

        $this->assertEquals('4008713700510', $good->getBarcode());

        $good = $parser->readNextElement();

        $this->assertEquals('2808650', $good->getBarcode());

        $good = $parser->readNextElement();

        $this->assertEquals('2809733', $good->getBarcode());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testManufacturerNameParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('Россия', $good->getManufacturerName());

        $good = $parser->readNextElement();

        $this->assertEquals('Германия', $good->getManufacturerName());

        $good = $parser->readNextElement();

        $this->assertEquals('Кулинария Лэнд', $good->getManufacturerName());

        $good = $parser->readNextElement();

        $this->assertEquals('Кулинария Лэнд', $good->getManufacturerName());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }

    public function testPriceParse()
    {
        $parser = $this->createXmlParser();

        $good = $parser->readNextElement();

        $this->assertEquals('50.77', $good->getPrice());

        $good = $parser->readNextElement();

        $this->assertEquals('94.00', $good->getPrice());

        $good = $parser->readNextElement();

        $this->assertEquals('1100.00', $good->getPrice());

        $good = $parser->readNextElement();

        $this->assertEquals('440.00', $good->getPrice());

        $good = $parser->readNextElement();

        $this->assertFalse($good);
    }
}
