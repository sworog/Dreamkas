<?php

namespace Lighthouse\CoreBundle\Tests\Versionable;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Doctrine\ODM\MongoDB\DocumentManager;

class VersionableFactoryTest extends ContainerAwareTestCase
{
    public function testCreateVersionable()
    {
        $this->clearMongoDb();

        /* @var VersionFactory $factory */
        $factory = $this->getContainer()->get('lighthouse.core.versionable.factory');
        /* @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $product = new Product();
        $product->name = 'Кефир "Веселый Молочник" 1% 950гр';
        $product->units = 'gr';
        $product->barcode = '4607025392408';
        $product->purchasePrice = new Money(30.48);
        $product->sku = 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР';
        $product->vat = 10;
        $product->vendor = 'Вимм-Билль-Данн';
        $product->vendorCountry = 'Россия';
        $product->info = 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса';

        $subCategory = new SubCategory();
        $subCategory->name = 'Кефир 1%';

        $product->subCategory = $subCategory;

        $dm->persist($product);
        $dm->flush();

        $productVersion = $factory->createVersion($product);

        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\Product\\ProductVersion', $productVersion);
        $this->assertEquals($product->name, $productVersion->name);
        $this->assertEquals($product->units, $productVersion->units);
        $this->assertEquals($product->barcode, $productVersion->barcode);
        $this->assertNotNull($productVersion->getVersion());
        $this->assertSame($product, $productVersion->getObject());

        $dm->persist($productVersion);
        $dm->flush();
    }
}
