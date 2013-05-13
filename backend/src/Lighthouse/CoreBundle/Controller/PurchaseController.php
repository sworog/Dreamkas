<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Purchase\Purchase;
use Lighthouse\CoreBundle\Document\Purchase\PurchaseRepository;
use Lighthouse\CoreBundle\Form\PurchaseType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class PurchaseController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.purchase")
     * @var PurchaseRepository
     */
    protected $purchaseRepository;

    /**
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\Purchase\Purchase
     */
    public function postPurchasesAction(Request $request)
    {
        $purchase = new Purchase();
        $form = $this->createForm(new PurchaseType(), $purchase);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->purchaseRepository->getDocumentManager()->persist($purchase);
                $this->purchaseRepository->getDocumentManager()->flush();
                return $purchase;
            }
        }

        return $this->view($form, 400);
    }
}
