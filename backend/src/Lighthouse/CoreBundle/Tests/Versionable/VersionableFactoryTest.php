<?php

namespace Lighthouse\CoreBundle\Tests\Versionable;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Versionable\VersionableInterface;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;

class VersionableFactoryTest extends ContainerAwareTestCase
{
    public function testCreateVersionable()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $product = $this->createProduct();

        $this->getDocumentManager()->persist($product);
        $this->getDocumentManager()->flush();

        /* @var VersionFactory $versionFactory */
        $versionFactory = $this->getContainer()->get('lighthouse.core.versionable.factory');
        /* @var ProductVersion $productVersion */
        $productVersion = $versionFactory->createDocumentVersion($product);

        $version = $productVersion->getVersion();

        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\Product\\Version\\ProductVersion', $productVersion);
        $this->assertEquals($product->name, $productVersion->name);
        $this->assertEquals($product->barcode, $productVersion->barcode);
        $this->assertNotNull($productVersion->getVersion());
        $this->assertSame($product, $productVersion->getObject());

        $this->getDocumentManager()->persist($productVersion);
        $this->getDocumentManager()->flush();

        $this->assertEquals($version, $productVersion->getVersion());
    }

    public function testFindByDocument()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $product = $this->createProduct();

        $this->getDocumentManager()->persist($product);
        $this->getDocumentManager()->flush();

        /* @var VersionRepository $productVersionRepo */
        $productVersionRepo = $this->getContainer()->get('lighthouse.core.document.repository.product_version');
        $productVersion = $productVersionRepo->findOrCreateByDocument($product);

        $state = $this->getDocumentManager()->getUnitOfWork()->getDocumentState($productVersion);
        $this->assertEquals(UnitOfWork::STATE_MANAGED, $state);

        $this->getDocumentManager()->persist($productVersion);
        $this->getDocumentManager()->flush();

        $this->getDocumentManager()->clear();

        $foundProductVersion = $productVersionRepo->findOrCreateByDocument($product);

        $this->assertEquals($productVersion->getVersion(), $foundProductVersion->getVersion());
        $this->assertNotSame($productVersion, $foundProductVersion);
    }

    /**
     * @return Product
     */
    protected function createProduct()
    {
        $product = new Product();
        $product->name = 'Кефир "Веселый Молочник" 1% 950гр';
        $product->barcode = '4607025392408';
        $product->purchasePrice = new Money(30.48);
        $product->sku = 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР';
        $product->vat = 10;
        $product->vendor = 'Вимм-Билль-Данн';
        $product->vendorCountry = 'Россия';
        $product->info = 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса';

        $roundingManager = $this->getContainer()->get('lighthouse.core.rounding.manager');
        $rounding = $roundingManager->findDefault();

        $subCategory = new SubCategory();
        $subCategory->name = 'Кефир 1%';
        $subCategory->rounding = $rounding;

        $product->subCategory = $subCategory;
        return $product;
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Document 'ProductMock' is not versionable
     */
    public function testGetDocumentNotVersionable()
    {
        /* @var VersionableInterface|\PHPUnit_Framework_MockObject_MockObject $versionableDocument */
        $versionableDocument = $this->getMock(
            'Lighthouse\CoreBundle\Versionable\VersionableInterface',
            array('getVersionClass'),
            array(),
            'ProductMock'
        );
        $versionableDocument->expects($this->once())
            ->method('getVersionClass')
            ->will($this->returnValue('Lighthouse\CoreBundle\Document\User\User'));

        /* @var DocumentManager|\PHPUnit_Framework_MockObject_MockObject $dm */
        $dm = $this->getMock(
            'Doctrine\ODM\MongoDB\DocumentManager',
            array(),
            array(),
            '',
            false
        );

        $dm->expects($this->at(0))
           ->method('getClassMetadata')
           ->with($this->equalTo('ProductMock'))
           ->will($this->returnValue(new ClassMetadata('ProductMock')));
        $dm->expects($this->at(1))
            ->method('getClassMetadata')
            ->with($this->equalTo('Lighthouse\CoreBundle\Document\User\User'))
            ->will($this->returnValue(new ClassMetadata('Lighthouse\CoreBundle\Document\User\User')));

        $versionFactory = new VersionFactory($dm);

        $versionFactory->createDocumentVersion($versionableDocument);
    }
}
