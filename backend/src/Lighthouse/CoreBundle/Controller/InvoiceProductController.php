<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class InvoiceProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.invoice_product")
     * @var StockMovementProductRepository
     */
    protected $documentRepository;

    /**
     * @param Store $store
     * @param Product $product
     * @return Cursor|InvoiceProduct[]
     * @Rest\Route("stores/{store}/products/{product}/invoiceProducts")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreProductInvoiceProductsAction(Store $store, Product $product)
    {
        return $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
    }
}
