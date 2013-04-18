<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\Test;
use JMS\Serializer\Serializer;
use Lighthouse\CoreBundle\Types\Money;

class DocumentSerializerTest extends WebTestCase
{
    public function testDocumentSerializeXml()
    {
        $document = $this->createDocument();

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($document, 'xml');

        $expectedFile = __DIR__ . '/../Fixtures/Document/Test.xml';
        $this->assertXmlStringEqualsXmlFile($expectedFile, $result);
    }

    public function testDocumentSerializeJson()
    {
        $document = $this->createDocument();

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($document, 'json');

        $expectedFile = __DIR__ . '/../Fixtures/Document/Test.json';
        $this->assertJsonStringEqualsJsonFile($expectedFile, $result);
    }

    /**
     * @return Test
     */
    public function createDocument()
    {
        $document = new Test();
        $document->id = 1;
        $document->name = 'name_1';
        $document->desc = 'description_1';
        $document->orderDate = new \DateTime('2013-03-01');
        $document->createdDate = new \DateTime('2013-02-28 15:40');
        $document->money = new Money(1112);

        return $document;
    }
}
