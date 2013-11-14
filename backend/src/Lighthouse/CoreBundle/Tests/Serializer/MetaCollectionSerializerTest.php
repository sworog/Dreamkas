<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Meta\MetaCollection;
use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\Test;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestCollection;
use JMS\Serializer\Serializer;

class MetaCollectionSerializerTest extends ContainerAwareTestCase
{
    public function testCollectionSerializeJson()
    {
        $testMeta = array(
            1 => array(
                'test_meta1' => 'test_meta_value1',
            ),
            2 => array(
                'test_meta2' => 'test_meta_value2',
            ),
            3 => array(
                'test_meta3' => 'test_meta_value3',
            ),
            4 => array(
                'test_meta4' => 'test_meta_value4',
            ),
            5 => array(
                'test_meta5' => 'test_meta_value5',
            )
        );

        /* @var MetaGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $mockMetaGenerator */
        $mockMetaGenerator = $this->getMock(
            '\Lighthouse\CoreBundle\Meta\MetaGeneratorInterface'
        );

        $mockMetaGenerator
            ->expects($this->any())
            ->method('getMetaForElement')
            ->will(
                $this->onConsecutiveCalls(
                    $testMeta[1],
                    $testMeta[2],
                    $testMeta[3],
                    $testMeta[4],
                    $testMeta[5]
                )
            );

        $collection = $this->createCollection();
        $metaCollection = new MetaCollection($collection);
        $metaCollection->addMetaGenerator($mockMetaGenerator);

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($metaCollection, 'json');

        $expectedFile = $this->getFixtureFilePath('Document/Meta/TestCollection.json');
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
