<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

class OrderController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.order")
     * @var OrderRepository
     */
    protected $documentRepository;

    /**
     * @return OrderType
     */
    protected function getDocumentFormType()
    {
        return new OrderType();
    }

    /**
     * @param Store $store
     * @param Request $request
     * @return View|Order
     *
     * @Rest\View(statusCode=201)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function postOrderAction(Store $store, Request $request)
    {
        $order = new Order;
        $order->store = $store;
        return $this->processForm($request, $order);
    }
}
