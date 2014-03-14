<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Order\Product\OrderProduct;
use Lighthouse\CoreBundle\Document\Order\Product\OrderProductRepository;
use Lighthouse\CoreBundle\Form\OrderProductType;
use Lighthouse\CoreBundle\Document\Store\Store;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class OrderProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.order_product")
     * @var OrderProductRepository
     */
    protected $documentRepository;

    /**
     * @return OrderProductType
     */
    protected function getDocumentFormType()
    {
        return new OrderProductType();
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return View|OrderProduct
     *
     * @Rest\View(statusCode=200)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postProductsAction(Store $store, Request $request)
    {
        $orderProduct = new OrderProduct();
        return $this->processForm($request, $orderProduct, false);
    }
}
