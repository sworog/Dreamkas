<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Lighthouse\CoreBundle\Document\InvoiceProductRepository;
use JMS\DiExtraBundle\Annotation as DI;

class InvoiceProductController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.invoice_product")
     * @var InvoiceProductRepository
     */
    protected $repository;

    public function postProductAction($slug)
    {

    }
}
