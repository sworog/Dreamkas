<?php

namespace Lighthouse\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\StockMovement\Returne\ReturnType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;

class ReturnController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.receipt")
     * @var ReceiptRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface
     */
    protected function getDocumentFormType()
    {
        return new ReturnType();
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return FormInterface|Returne
     *
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postStoreReturnsAction(Request $request, Store $store)
    {
        $receipt = $this->documentRepository->createNewByType(Returne::TYPE);
        $receipt->store = $store;
        return $this->processForm($request, $receipt);
    }
}
