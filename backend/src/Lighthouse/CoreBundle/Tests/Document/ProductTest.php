<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Test\TestCase;

class ProductTest extends TestCase
{
    public function testConstruct()
    {
        $product = new Product();
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\Product\\Product', $product);
    }

    public function testGetSetProperties()
    {
        $product = new Product();

        $product->name = 'name';
        $this->assertEquals('name', $product->name);

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

    public function testPopulateAndToArray()
    {
        $array = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $product = new Product();
        $product->populate($array);

        foreach ($array as $key => $value) {
            $this->assertEquals($value, $product->$key);
        }
    }
}
