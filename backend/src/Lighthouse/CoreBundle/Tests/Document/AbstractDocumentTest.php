<?php

namespace Lighthouse\CoreBundle\Tests\Entity;

use Lighthouse\CoreBundle\Tests\Fixtures\Document\TestDocument;

class AbstractDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetProperties()
    {
        $document = new TestDocument();

        $document->name = 'name';
        $this->assertEquals('name', $document->name);

        $document->desc = 'info';
        $this->assertEquals('info', $document->desc);

        $this->assertNull($document->id);
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidPropertyGet()
    {
        $product = new TestDocument();

        $product->invalid;
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidPropertySet()
    {
        $document = new TestDocument();

        $document->invalid = 'invalid';
    }

    public function testPopulateAndToArray()
    {
        $array = array(
            'id' => 1,
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'desc' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $document = new TestDocument();
        $document->populate($array);

        $productArray = $document->toArray();
        foreach ($array as $key => $value) {
            $this->assertEquals($value, $productArray[$key]);
        }
    }
}
