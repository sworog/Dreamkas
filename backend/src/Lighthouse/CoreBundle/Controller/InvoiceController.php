<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceCollection;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceHighlightGenerator;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\InvoicesFilter;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductCollection;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\InvoiceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Meta\MetaCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use MongoDuplicateKeyException;

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

    protected function handleFlushFailedException(FlushFailedException $e)
    {
        if ($e->getCause() instanceof MongoDuplicateKeyException) {
            return $this->addFormError(
                $e->getForm(),
                'order',
                'lighthouse.validation.errors.invoice.order.unique'
            );
        } else {
            return parent::handleFlushFailedException($e);
        }
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return View|Invoice
     *
     * @Rest\View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postInvoicesAction(Store $store, Request $request)
    {
        $invoice = $this->documentRepository->createNew();
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
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function putInvoicesAction(Store $store, Invoice $invoice, Request $request)
    {
        $this->checkInvoiceStore($store, $invoice);
        foreach ($invoice->products as $key => $invoiceProduct) {
            unset($invoice->products[$key]);
            $this->documentRepository->getDocumentManager()->remove($invoiceProduct);
        }
        $invoice->products = new InvoiceProductCollection();
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
        if ($filter->hasNumberOrSupplierInvoiceNumber()) {
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
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getInvoiceAction(Store $store, Invoice $invoice)
    {
        $this->checkInvoiceStore($store, $invoice);
        return $invoice;
    }

    /**
     * @param Store $store
     * @param Order $order
     * @throws NotFoundHttpException
     * @return Invoice
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getOrderInvoiceAction(Store $store, Order $order)
    {
        if ($order->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", Order::getClassName()));
        }
        $invoice = $this->documentRepository->createByOrder($order);
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
