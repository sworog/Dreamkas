<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\InvoiceProductType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class InvoiceProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice_product")
     * @var InvoiceProductRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice")
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @return InvoiceProductType
     */
    protected function getDocumentFormType()
    {
        return new InvoiceProductType();
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return Cursor|InvoiceProduct[]
     * @Rest\Route("stores/{store}/products/{product}/invoiceProducts")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductInvoiceProductsAction(Store $store, Product $product)
    {
        return $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @throws NotFoundHttpException
     */
    protected function checkInvoiceStore(Invoice $invoice, Store $store)
    {
        if ($invoice->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($invoice)));
        }
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @param Invoice $invoice
     * @param Store $store
     * @throws NotFoundHttpException
     */
    protected function checkInvoiceProduct(InvoiceProduct $invoiceProduct, Invoice $invoice, Store $store)
    {
        $this->checkInvoiceStore($invoice, $store);
        if ($invoiceProduct->invoice !== $invoice) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($invoiceProduct)));
        }
    }
}
