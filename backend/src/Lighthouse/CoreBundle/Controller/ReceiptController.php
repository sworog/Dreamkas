<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\StockMovement\ReceiptType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class ReceiptController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.receipt")
     * @var ReceiptRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface|null
     */
    protected function getDocumentFormType()
    {
        return null;
    }

    /**
     * @param Request $request
     * @param Store $store
     * @param string $type
     * @return FormInterface|Receipt
     *
     * @Rest\Route("stores/{store}/sales", defaults={"type"="Sale"})
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postStoreAction(Request $request, Store $store, $type)
    {
        $receipt = $this->documentRepository->createNewByType($type);
        $receipt->store = $store;
        $receiptType = new ReceiptType($type);
        return $this->processForm($request, $receipt, $receiptType);
    }
}
