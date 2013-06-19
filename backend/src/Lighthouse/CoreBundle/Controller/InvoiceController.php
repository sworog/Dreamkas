<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceCollection;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Form\InvoiceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Invoice\Invoice
     *
     * @Rest\View(statusCode=201)
     */
    public function postInvoicesAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Invoice\Invoice
     *
     * @Rest\View(statusCode=200)
     */
    public function putInvoicesAction(Request $request, $id)
    {
        return $this->processPut($request, $id);
    }

    /**
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Invoice\InvoiceCollection
     */
    public function getInvoicesAction()
    {
        /* @var LoggableCursor $cursor */
        $cursor = $this->getDocumentRepository()->findAll();
        $collection = new InvoiceCollection($cursor);
        return $collection;
    }

    /**
     * @param int $id
     * @return \Lighthouse\CoreBundle\Document\Invoice\Invoice
     */
    public function getInvoiceAction($id)
    {
        return $this->processGet($id);
    }
}
