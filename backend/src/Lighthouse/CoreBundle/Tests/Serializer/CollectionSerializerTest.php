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
        for ($i = 1; $i <= 5; $i++) {
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
        <name><![CDATA[name_1]]></name>
        <desc><![CDATA[description_1]]></desc>
        <orderDate><![CDATA[01.03.2013]]></orderDate>
    </test>
    <test>
        <id>2</id>
        <name><![CDATA[name_2]]></name>
        <desc><![CDATA[description_2]]></desc>
        <orderDate><![CDATA[02.03.2013]]></orderDate>
    </test>
    <test>
        <id>3</id>
        <name><![CDATA[name_3]]></name>
        <desc><![CDATA[description_3]]></desc>
        <orderDate><![CDATA[03.03.2013]]></orderDate>
    </test>
    <test>
        <id>4</id>
        <name><![CDATA[name_4]]></name>
        <desc><![CDATA[description_4]]></desc>
        <orderDate><![CDATA[04.03.2013]]></orderDate>
    </test>
    <test>
        <id>5</id>
        <name><![CDATA[name_5]]></name>
        <desc><![CDATA[description_5]]></desc>
        <orderDate><![CDATA[05.03.2013]]></orderDate>
    </test>
</tests>';
        $this->assertXmlStringEqualsXmlString($expected, $result);
    }
}