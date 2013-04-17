<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Invoice;
use Lighthouse\CoreBundle\Document\InvoiceProduct;
use Lighthouse\CoreBundle\Document\InvoiceProductCollection;
use Lighthouse\CoreBundle\Document\InvoiceProductRepository;
use Lighthouse\CoreBundle\Document\InvoiceRepository;
use Lighthouse\CoreBundle\Form\InvoiceProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation as DI;

class InvoiceProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice_product")
     * @var InvoiceProductRepository
     */
    protected $invoiceProductRepository;

    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice")
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $invoiceId
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Invoice
     *
     * @Rest\View(statusCode=201)
     */
    public function postProductsAction(Request $request, $invoiceId)
    {
        $invoice = $this->findInvoice($invoiceId);
        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->invoice = $invoice;
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param Request $request
     * @param string $invoiceId
     * @param string $invoiceProductId
     *
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\InvoiceProduct
     *
     * @Rest\View(statusCode=200)
     */
    public function putProductsAction(Request $request, $invoiceId, $invoiceProductId)
    {
        $invoiceProduct = $this->findInvoiceProduct($invoiceProductId, $invoiceId);
        return $this->processForm($request, $invoiceProduct);
    }

    /**
     * @param string $invoiceId
     * @return InvoiceProductCollection
     */
    public function getProductAction($invoiceId, $invoiceProductId)
    {
        return $this->findInvoiceProduct($invoiceProductId, $invoiceId);
    }

    /**
     * @param string $invoiceId
     * @return InvoiceProductCollection
     */
    public function getProductsAction($invoiceId)
    {
        $invoice = $this->findInvoice($invoiceId);
        $invoiceProducts = $this->invoiceProductRepository->findByInvoice($invoice->id);
        return new InvoiceProductCollection($invoiceProducts);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Lighthouse\CoreBundle\Document\InvoiceProduct $invoiceProduct
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\InvoiceProduct
     */
    protected function processForm(Request $request, InvoiceProduct $invoiceProduct)
    {
        $invoiceType = new InvoiceProductType();

        $form = $this->createForm($invoiceType, $invoiceProduct);
        $form->bind($request);

        if ($form->isValid()) {
            $this->invoiceRepository->getDocumentManager()->persist($invoiceProduct);
            $this->invoiceRepository->getDocumentManager()->flush();
            return $invoiceProduct;
        } else {
            return View::create($form, 400);
        }
    }

    /**
     * @param string $invoiceId
     * @return Invoice
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findInvoice($invoiceId)
    {
        $invoice = $this->invoiceRepository->find($invoiceId);
        if (null === $invoice) {
            throw new NotFoundHttpException("Invoice not found");
        }
        return $invoice;
    }

    /**
     * @param string $invoiceProductId
     * @param string $invoiceId
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return InvoiceProduct
     */
    protected function findInvoiceProduct($invoiceProductId, $invoiceId)
    {
        $invoiceProduct = $this->invoiceProductRepository->find($invoiceProductId);
        if (null === $invoiceProduct) {
            throw new NotFoundHttpException("InvoiceProduct not found");
        } elseif ($invoiceProduct->invoice->id != $invoiceId) {
            throw new NotFoundHttpException("InvoiceProduct not found");
        }
        return $invoiceProduct;
    }
}
