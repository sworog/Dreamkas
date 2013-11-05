<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Restitution\Product\RestitutionProductCollection;
use Lighthouse\CoreBundle\Document\Restitution\Product\RestitutionProductRepository;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lighthouse\CoreBundle\Document\Store\Store;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ReturnProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.restitution.product")
     * @var RestitutionProductRepository
     */
    protected $documentRepository;

    /**
     * @param Store $store
     * @param Product $product
     * @return RestitutionProductCollection
     * @Rest\Route("stores/{store}/products/{product}/returnProducts")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreProductReturnProductsAction(Store $store, Product $product)
    {
        $cursor = $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
        return new RestitutionProductCollection($cursor);
    }
}
