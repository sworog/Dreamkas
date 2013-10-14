<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\ImportSales;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\ImportSalesXmlParser;
use Lighthouse\CoreBundle\Integration\Set10\ImportSales\PurchaseElement;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use DateTime;

class ImportSalesXmlParserTest extends ContainerAwareTestCase
{
    /**
     * @param string $xmlFile
     * @return ImportSalesXmlParser
     */
    protected function createXmlParser($xmlFile = 'Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml')
    {
        return new ImportSalesXmlParser($this->getFixtureFilePath($xmlFile));
    }

    public function testReadPurchasesCount()
    {
        $xmlParser = $this->createXmlParser();
        $count = $xmlParser->readPurchasesCount();
        $this->assertEquals(20, $count);
    }

    public function testReadPurchasesCountAfterReadNextElementReturnsNull()
    {
        $xmlParser = $this->createXmlParser();
        $element = $xmlParser->readNextElement();
        $this->assertNotEquals(false, $element);

        $count = $xmlParser->readPurchasesCount();
        $this->assertNull($count);
    }

    public function testReadNextNode()
    {
        $xmlParser = $this->createXmlParser();

        $purchaseElement = $xmlParser->readNextElement();

        $this->assertInstanceOf(PurchaseElement::getClassName(), $purchaseElement);

        $saleTime = $purchaseElement->getSaleTime();
        $this->assertEquals('2012-05-12T17:17:49.684+04:00', $saleTime);

        $saleDateTime = $purchaseElement->getSaleDateTime();
        $this->assertInstanceOf('\DateTime', $saleDateTime);
        $this->assertEquals('2012-05-12T17:17:49+04:00', $saleDateTime->format(DateTime::ATOM));
        $this->assertEquals('49.684', $saleDateTime->format('s.u'));

        $this->assertEquals('197', $purchaseElement->getShop());

        $positions = $purchaseElement->getPositions();
        $this->assertInternalType('array', $positions);
        $this->assertCount(1, $positions);

        $position = $positions[0];
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Integration\\Set10\\ImportSales\\PositionElement', $position);

        $this->assertEquals('3.5', $position->getCost());
        $this->assertEquals('10.0', $position->getCount());
        $this->assertEquals('1', $position->getGoodsCode());
        $this->assertEquals('31.5', $position->getAmount());

        $purchaseElement = $xmlParser->readNextElement();

        $this->assertInstanceOf(PurchaseElement::getClassName(), $purchaseElement);
        $positions = $purchaseElement->getPositions();
        $this->assertCount(3, $positions);
    }

    public function testReadNextNodeReturnFalseIfAllElementsAreRead()
    {
        $xmlParser = $this->createXmlParser();

        for ($i = 0; $i < 20; $i++) {
            $purchaseElement = $xmlParser->readNextElement();
            $this->assertInstanceOf(PurchaseElement::getClassName(), $purchaseElement);
        }

        $purchaseElement = $xmlParser->readNextElement();
        $this->assertFalse($purchaseElement);

        $purchaseElement = $xmlParser->readNextElement();
        $this->assertFalse($purchaseElement);
    }
}
