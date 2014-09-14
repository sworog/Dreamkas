<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\StockMovement\Sale\SaleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class SaleController extends AbstractRestController
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
        return new SaleType();
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return FormInterface|Sale
     *
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postStoreSalesAction(Request $request, Store $store)
    {
        $receipt = $this->documentRepository->createNewByType(Sale::TYPE);
        $receipt->store = $store;
        return $this->processForm($request, $receipt);
    }

    /**
     * @param Store $store
     * @param Sale $sale
     * @return Sale
     *
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     *
     * @ParamConverter("sale", options={"mapping": {"store": "store", "id": "sale"}})
     */
    public function getStoreSaleAction(Store $store, Sale $sale)
    {
        return $sale;
    }
}
