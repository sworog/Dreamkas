<?php

namespace LightHouse\CoreBundle\Tests\DataMapper;

use LightHouse\CoreBundle\DataMapper\ProductMapper;
use LightHouse\CoreBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class ProductTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initKernel();
        $this->clearMongoDb();
    }

    protected function initKernel()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return static::$kernel->getContainer();
    }

    protected function clearMongoDb()
    {
        /* @var \MongoDB $mongoDB */
        $mongoDB = $this->getContainer()->get('light_house_core.db.mongo.db');
        $mongoDB->drop();
    }

    /**
     * @return ProductMapper
     */
    protected function getProductMapper()
    {
        return $this->getContainer()->get('light_house_core.mapper.product');
    }

    public function testCreate()
    {
        $mapper = $this->getProductMapper();

        $product = new Product();
        $product->name = 'Кефир "Веселый Молочник" 1% 950гр';
        $product->units = 'gr';
        $product->barcode = '4607025392408';
        $product->purchasePrice = 3048;
        $product->sku = 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР';
        $product->vat = 10;
        $product->vendor = 'Вимм-Билль-Данн';
        $product->vendorCountry = 'Россия';
        $product->info = 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса';

        $this->assertNull($product->id);

        $result = $mapper->create($product);

        $this->assertTrue($result);

        $this->assertNotNull($product->id);
        $this->assertNotInstanceOf('MongoId', $product->id);
    }
}
