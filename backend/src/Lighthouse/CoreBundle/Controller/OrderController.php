<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\File\FileUploader;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderCollection;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\OrderType;
use Lighthouse\CoreBundle\Integration\Excel\Export\Orders\OrderGenerator;
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
     * @return Order
     * @SecureParam(name="store", permissions="ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getOrdersAction(Store $store)
    {
        $orders = $this->getDocumentRepository()->findAll();
        $orderCollection = new OrderCollection($orders);
        return $orderCollection;
    }

    /**
     * @param Store $store
     * @param Order $order
     * @throws \HttpResponseException
     * @return StreamedResponse
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
