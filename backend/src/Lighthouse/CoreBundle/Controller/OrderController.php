<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderCollection;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @param Store $store
     * @param Order $order
     * @param Request $request
     * @return View|Order
     *
     * @Rest\View(statusCode=200)
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function putOrderAction(Store $store, Order $order, Request $request)
    {
        $this->checkOrderStore($store, $order);
        return $this->processForm($request, $order);
    }

    /**
     * @param Store $store
     * @param Order $order
     * @return Order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getOrderAction(Store $store, Order $order)
    {
        $this->checkOrderStore($store, $order);
        return $order;
    }


    /**
     * @param Store $store
     * @return Order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getOrdersAction(Store $store)
    {
        $orders = $this->documentRepository->findAllByStoreId($store->id);
        return new OrderCollection($orders);
    }

    /**
     * @param Store $store
     * @param Order $order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     * @return null
     */
    public function deleteOrderAction(Store $store, Order $order)
    {
        $this->checkOrderStore($store, $order);
        return $this->processDelete($order);
    }

    /**
     * @param Store $store
     * @param Order $order
     * @throws NotFoundHttpException
     */
    protected function checkOrderStore(Store $store, Order $order)
    {
        if ($order->store !== $store) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($order)));
        }
    }
}
