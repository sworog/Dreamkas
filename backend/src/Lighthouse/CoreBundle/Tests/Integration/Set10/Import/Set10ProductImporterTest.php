<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\Import;

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

    public function testOnlyGroupNodesAreRead()
    {
        $importer = $this->createImporter();
        $groupNodesCount = 0;
        while ($simpleXml = $importer->readNextNode()) {
            $this->assertInstanceOf('\SimpleXmlElement', $simpleXml);
            $this->assertEquals('good', $simpleXml->getName());
            $groupNodesCount++;
        }
        $this->assertEquals(4, $groupNodesCount);
    }
}
