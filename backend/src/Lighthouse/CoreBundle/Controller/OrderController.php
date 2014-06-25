<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderCollection;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Order\OrdersFilter;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\OrderType;
use Lighthouse\CoreBundle\Integration\Excel\Export\Orders\OrderGenerator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.order")
     * @var OrderRepository
     */
    protected $documentRepository;

    /**
     * @DI\Inject("lighthouse.core.integration.excel.export.orders.generator")
     *
     * @var OrderGenerator
     */
    protected $orderGenerator;

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
     * @return FormInterface|Order
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
     * @return FormInterface|Order
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
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc
     */
    public function getOrderAction(Store $store, Order $order)
    {
        $this->checkOrderStore($store, $order);
        return $order;
    }


    /**
     * @param Store $store
     * @param OrdersFilter $ordersFilter
     * @return Order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @Rest\Route("stores/{store}/orders")
     * @ApiDoc(resource=true)
     */
    public function getOrdersAction(Store $store, OrdersFilter $ordersFilter)
    {
        $orders = $this->documentRepository->findAllByStoreId($store->id, $ordersFilter);
        return new OrderCollection($orders);
    }

    /**
     * @param Store $store
     * @param Order $order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     * @return void
     */
    public function deleteOrderAction(Store $store, Order $order)
    {
        $this->checkOrderStore($store, $order);
        $this->processDelete($order);
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

    /**
     * @param Store $store
     * @param Order $order
     * @throws \HttpResponseException
     * @return StreamedResponse
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getOrderDownloadAction(Store $store, Order $order)
    {
        $this->checkOrderStore($store, $order);

        $this->orderGenerator->setOrder($order);
        $this->orderGenerator->generate();

        $fileUploader = $this->container->get('lighthouse.core.document.repository.file.uploader');

        $file = $fileUploader->processWriter(
            $this->orderGenerator->getWriter(),
            $this->orderGenerator->getFilename(),
            'orders'
        );

        return $file;
    }
}
