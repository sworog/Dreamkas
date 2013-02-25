<?php

namespace LightHouse\CoreBundle\Tests\Entity;

use LightHouse\CoreBundle\Entity\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $product = new Product();
        $this->assertInstanceOf('LightHouse\\CoreBundle\\Entity\\Product', $product);
    }

    public function testGetSetProperties()
    {
        $product = new Product();

        $product->name = 'name';
        $this->assertEquals('name', $product->name);

        $product->units = 'kg';
        $this->assertEquals('kg', $product->units);

        $product->vat = 10;
        $this->assertEquals(10, $product->vat);

        $product->purchasePrice = 335.5;
        $this->assertEquals(335.5, $product->purchasePrice);

        $product->barcode = '909283372';
        $this->assertEquals('909283372', $product->barcode);

        $product->sku = '9782436723g23r';
        $this->assertEquals('9782436723g23r', $product->sku);

        $product->vendorCountry = 'Russia';
        $this->assertEquals('Russia', $product->vendorCountry);

        $product->vendor = 'Vendor';
        $this->assertEquals('Vendor', $product->vendor);

        $product->info = 'info';
        $this->assertEquals('info', $product->info);

        $this->assertNull($product->id);
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidPropertyGet()
    {
        $product = new Product();

        $product->invalid;
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidPropertySet()
    {
        $product = new Product();

        $product->invalid = 'invalid';
    }
}