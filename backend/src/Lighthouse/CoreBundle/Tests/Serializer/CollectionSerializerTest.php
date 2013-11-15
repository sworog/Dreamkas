<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Serializer\Handler\CollectionHandler;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\Test;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestCollection;
use JMS\Serializer\Serializer;

class CollectionSerializerTest extends ContainerAwareTestCase
{
    public function testCollectionSerializeXml()
    {
        $collection = $this->createCollection();

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($collection, 'xml');

        $expectedFile = $this->getFixtureFilePath('Document/TestCollection.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, $result);
    }

    public function testCollectionSerializeJson()
    {
        $collection = $this->createCollection();

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($collection, 'json');

        $expectedFile = $this->getFixtureFilePath('Document/TestCollection.json');
        $this->assertJsonStringEqualsJsonFile($expectedFile, $result);
    }

    /**
     * @return TestCollection
     */
    protected function createCollection()
    {
        $collection = new TestCollection();
        for ($i = 1; $i <= 5; $i++) {
            $document = new Test();
            $document->id = $i;
            $document->name = 'name_' . $i;
            $document->desc = 'description_' . $i;
            $document->orderDate = '0' . $i . '.03.2013';
            $collection->add($document);
        }
        return $collection;
    }
}
