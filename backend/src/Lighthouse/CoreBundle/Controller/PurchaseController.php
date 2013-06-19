<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Purchase\Purchase;
use Lighthouse\CoreBundle\Document\Purchase\PurchaseRepository;
use Lighthouse\CoreBundle\Form\PurchaseType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class PurchaseController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.purchase")
     * @var PurchaseRepository
     */
    protected $documentRepository;

    /**
     * @return PurchaseType
     */
    protected function getDocumentFormType()
    {
        return new PurchaseType();
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Purchase\Purchase
     */
    public function postPurchasesAction(Request $request)
    {
        return $this->processPost($request);
    }
}
