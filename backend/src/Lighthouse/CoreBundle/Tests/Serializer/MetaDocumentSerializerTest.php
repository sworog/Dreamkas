<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Document\Meta\MetaDocument;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\Test;
use JMS\Serializer\Serializer;
use Lighthouse\CoreBundle\Types\Money;

class MetaDocumentSerializerTest extends ContainerAwareTestCase
{
    public function testDocumentSerializeJson()
    {
        $document = $this->createDocument();
        $metaDocument = new MetaDocument($document);
        $meta = array(
            'test_meta' => array(
                'key' => 'value',
            ),
        );
        $metaDocument->addMeta($meta);

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($metaDocument, 'json');

        $expectedFile = $this->getFixtureFilePath('Document/Meta/Test.json');
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
