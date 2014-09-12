<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class WriteOffProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.writeoff_product")
     * @var StockMovementProductRepository
     */
    protected $documentRepository;

    /**
     * @param Store $store
     * @param Product $product
     * @return WriteOffProduct[]|Cursor
     * @Rest\Route("stores/{store}/products/{product}/writeOffProducts")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductWriteOffProductsAction(Store $store, Product $product)
    {
        return $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
    }
}
