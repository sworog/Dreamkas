<?php

namespace Lighthouse\CoreBundle\Tests\Document\StoreProduct;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class StoreProductRepositoryTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage Empty store id
     */
    public function testGetIdByStoreAndProductInvalidStore()
    {
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $product = new Product();
        $product->id = '52a9c02a02af59f1158b456d';

        $store = new Store();
        $store->number = '1';
        $store->address = '1';
        $store->contacts = '1';

        $storeProductRepository->getIdByStoreAndProduct($store, $product);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage Empty product id
     */
    public function testGetIdByStoreAndProductInvalidProduct()
    {
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $product = new Product();

        $store = new Store();
        $store->id = '52a9c02a02af59f1158b456d';
        $store->number = '1';
        $store->address = '1';
        $store->contacts = '1';

        $storeProductRepository->getIdByStoreAndProduct($store, $product);
    }
}
