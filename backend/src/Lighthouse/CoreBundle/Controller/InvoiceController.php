<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceHighlightGenerator;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceFilter;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\StockMovement\InvoiceType;
use Lighthouse\CoreBundle\Meta\MetaCollection;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ODM\MongoDB\Cursor;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MongoDuplicateKeyException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvoiceController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.stock_movement.invoice")
     * @var InvoiceRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("validator")
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @return InvoiceType
     */
    protected function getDocumentFormType()
    {
        return new InvoiceType();
    }

    /**
     * @param FlushFailedException $e
     * @return Invoice|InvoiceType
     * @throws \Exception
     */
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
     * @param Request $request
     * @return FormInterface|Invoice
     *
     * @Rest\View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postInvoicesAction(Request $request)
    {
        $invoice = $this->documentRepository->createNew();
        $formType = new InvoiceType(true);
        return $this->processForm($request, $invoice, $formType);
    }

    /**
     * @param Invoice $invoice
     * @param Request $request
     * @return FormInterface|Invoice
     *
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putInvoicesAction(Invoice $invoice, Request $request)
    {
        $preViolations = $this->validator->validate($invoice, null, array('NotDeleted'));

        $formType = new InvoiceType(true);
        $this->documentRepository->resetProducts($invoice);
        return $this->processForm($request, $invoice, $formType, true, true, $preViolations);
    }

    /**
     * @param Invoice $invoice
     * @return Invoice
     *
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getInvoiceAction(Invoice $invoice)
    {
        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @return void
     *
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteInvoiceAction(Invoice $invoice)
    {
        $this->processDelete($invoice);
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return FormInterface|Invoice
     *
     * @Rest\View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postStoreInvoicesAction(Store $store, Request $request)
    {
        $invoice = $this->documentRepository->createNew();
        $invoice->store = $store;
        return $this->processForm($request, $invoice);
    }

    /**
     * @param Store $store
     * @param Invoice $invoice
     * @param Request $request
     * @return FormInterface|Invoice
     *
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function putStoreInvoicesAction(Store $store, Invoice $invoice, Request $request)
    {
        $this->checkInvoiceStore($store, $invoice);
        $this->documentRepository->resetProducts($invoice);
        return $this->processForm($request, $invoice);
    }

    /**
     * @param Store $store
     * @param InvoiceFilter $filter
     * @return MetaCollection|Invoice[]|Cursor
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     * @Rest\Route("stores/{store}/invoices")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     */
    public function getStoreInvoicesAction(Store $store, InvoiceFilter $filter)
    {
        $cursor = $this->documentRepository->findByStore($store->id, $filter);
        if ($filter->hasNumberOrSupplierInvoiceNumber()) {
            $collection = new MetaCollection(
                $cursor,
                new InvoiceHighlightGenerator($filter)
            );
        } else {
            $collection = $cursor;
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
    public function getStoreInvoiceAction(Store $store, Invoice $invoice)
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
    public function getStoreOrderInvoiceAction(Store $store, Order $order)
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
