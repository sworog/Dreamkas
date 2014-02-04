<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceCollection;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceHighlightGenerator;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\InvoicesFilter;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\InvoiceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Meta\MetaCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class InvoiceController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice")
     * @var InvoiceRepository
     */
    protected $documentRepository;

    /**
     * @return InvoiceType
     */
    protected function getDocumentFormType()
    {
        return new InvoiceType();
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return View|Invoice
     *
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postInvoicesAction(Store $store, Request $request)
    {
        $invoice = new Invoice;
        $invoice->store = $store;
        return $this->processForm($request, $invoice);
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @param Request $request
     * @return View|Invoice
     *
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putInvoicesAction(Store $store, Invoice $invoice, Request $request)
    {
        $this->checkInvoiceStore($store, $invoice);
        return $this->processForm($request, $invoice);
    }

    /**
     * @param Store $store
     * @param InvoicesFilter $filter
     * @return InvoiceCollection|MetaCollection
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     * @Rest\Route("stores/{store}/invoices")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getInvoicesAction(Store $store, InvoicesFilter $filter)
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->documentRepository->findByStore($store->id, $filter);
        if ($filter->hasSkuOrSupplierInvoiceSku()) {
            $highlightGenerator = new InvoiceHighlightGenerator($filter);
            $collection = new MetaCollection($cursor);
            $collection->addMetaGenerator($highlightGenerator);
        } else {
            $collection = new InvoiceCollection($cursor);
        }
        return $collection;
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @return Invoice
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getInvoiceAction(Store $store, Invoice $invoice)
    {
        $this->checkInvoiceStore($store, $invoice);
        return $invoice;
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @throws NotFoundHttpException
     */
    protected function checkInvoiceStore(Store $store, Invoice $invoice)
    {
        if ($invoice->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($invoice)));
        }
    }
}
