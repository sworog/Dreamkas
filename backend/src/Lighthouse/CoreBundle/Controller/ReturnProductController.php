<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Returne\Product\ReturnProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ReturnProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.return.product")
     * @var ReturnProductRepository
     */
    protected $documentRepository;

    /**
     * @param Store $store
     * @param Product $product
     * @return ReturnProduct[]|Cursor
     * @Rest\Route("stores/{store}/products/{product}/returnProducts")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreProductReturnProductsAction(Store $store, Product $product)
    {
        return $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
    }
}
