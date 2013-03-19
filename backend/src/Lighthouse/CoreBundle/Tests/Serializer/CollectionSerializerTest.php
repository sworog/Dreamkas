<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\Test;
use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestCollection;
use JMS\Serializer\Serializer;

class CollectionSerializerTest extends WebTestCase
{
    public function testCollectionSerialize()
    {
        $collection = new TestCollection();
        for ($i = 0; $i < 5; $i++) {
            $document = new Test();
            $document->id = $i;
            $document->name = 'name_' . $i;
            $document->desc = 'description_' . $i;
            $document->orderDate = '0' . $i . '.03.2013';
            $collection->add($document);
        }
        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');
        $result = $serializer->serialize($collection, 'xml');

        $expected = '<?xml version="1.0" encoding="UTF-8"?>
<tests>
    <test>
        <id>1</id>
        <name>name_1</name>
        <desc>description_1</desc>
        <orderDate>01.03.2013</orderDate>
    </test>
    <test>
        <id>2</id>
        <name>name_2</name>
        <desc>description_2</desc>
        <orderDate>02.03.2013</orderDate>
    </test>
    <test>
        <id>3</id>
        <name>name_3</name>
        <desc>description_3</desc>
        <orderDate>03.03.2013</orderDate>
    </test>
    <test>
        <id>4</id>
        <name>name_4</name>
        <desc>description_4</desc>
        <orderDate>04.03.2013</orderDate>
    </test>
    <test>
        <id>5</id>
        <name>name_5</name>
        <desc>description_5</desc>
        <orderDate>05.03.2013</orderDate>
    </test>
</tests>';
        $this->assertXmlStringEqualsXmlString($expected, $result);
    }
}