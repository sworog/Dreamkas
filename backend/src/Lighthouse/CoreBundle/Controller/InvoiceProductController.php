<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductCollection;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Form\InvoiceProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

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
     * @param Request $request
     * @param Invoice $invoice
     * @return View|Invoice
     *
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Request $request, Invoice $invoice)
    {
        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->invoice = $invoice;
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     *
     * @return \FOS\RestBundle\View\View|InvoiceProduct
     *
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putProductAction(Request $request, Invoice $invoice, InvoiceProduct $invoiceProduct)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice);
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     * @return InvoiceProductCollection
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductAction(Invoice $invoice, InvoiceProduct $invoiceProduct)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice);
        return $invoiceProduct;
    }

    /**
     * @param invoice $invoice
     * @return InvoiceProductCollection
     * @ApiDoc(
     *      resource=true
     * )
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getProductsAction(Invoice $invoice)
    {
        $invoiceProducts = $this->getDocumentRepository()->findByInvoice($invoice->id);
        return new InvoiceProductCollection($invoiceProducts);
    }

    /**
     * @param Invoice $invoice
     * @param InvoiceProduct $invoiceProduct
     * @return null
     * @Secure(roles="ROLE_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function deleteProductAction(Invoice $invoice, InvoiceProduct $invoiceProduct)
    {
        $this->checkInvoiceProduct($invoiceProduct, $invoice);
        return $this->processDelete($invoiceProduct);
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @param Invoice $invoice
     * @throws NotFoundHttpException
     */
    protected function checkInvoiceProduct(InvoiceProduct $invoiceProduct, Invoice $invoice)
    {
        if ($invoiceProduct->invoice->id != $invoice->id) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($invoice)));
        }
    }
}
