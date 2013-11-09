<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductCollection;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\InvoiceProductType;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param Invoice $invoice
     * @return View|Invoice
     *
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Store $store, Invoice $invoice, Request $request)
    {
        $this->checkInvoiceStore($invoice, $store);
        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->invoice = $invoice;
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     *
     * @return View|InvoiceProduct
     *
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putProductAction(Store $store, Invoice $invoice, InvoiceProduct $invoiceProduct, Request $request)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice, $store);
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     * @return InvoiceProductCollection
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductAction(Store $store, Invoice $invoice, InvoiceProduct $invoiceProduct)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice, $store);
        return $invoiceProduct;
    }

    /**
     * @param Store $store
     * @param invoice $invoice
     * @return InvoiceProductCollection
     * @ApiDoc(
     *      resource=true
     * )
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductsAction(Store $store, Invoice $invoice)
    {
        $this->checkInvoiceStore($invoice, $store);
        $invoiceProducts = $this->documentRepository->findByInvoice($invoice->id);
        return new InvoiceProductCollection($invoiceProducts);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return InvoiceProductCollection
     * @Rest\Route("stores/{store}/products/{product}/invoiceProducts")
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductInvoiceProductsAction(Store $store, Product $product)
    {
        $invoiceProducts = $this->documentRepository->findByStoreAndProduct($store->id, $product->id);
        return new InvoiceProductCollection($invoiceProducts);
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     * @return null
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function deleteProductAction(Store $store, Invoice $invoice, InvoiceProduct $invoiceProduct)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice, $store);
        return $this->processDelete($invoiceProduct);
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
