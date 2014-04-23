<?php

namespace Lighthouse\CoreBundle\Test\Factory\Order;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Order\Product\OrderProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Validator\ValidatorInterface;

class OrderBuilder
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param Factory $factory
     * @param OrderRepository $orderRepository
     * @param ValidatorInterface $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        Factory $factory,
        OrderRepository $orderRepository,
        ValidatorInterface $validator,
        NumericFactory $numericFactory
    ) {
        $this->factory = $factory;
        $this->orderRepository = $orderRepository;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Store $store
     * @param Supplier $supplier
     * @param string $createdDate
     * @return OrderBuilder
     */
    public function createOrder(Store $store = null, Supplier $supplier = null, $createdDate = null)
    {
        $supplier = ($supplier) ?: $this->factory->supplier()->getSupplier();

        $store = ($store) ?: $this->factory->store()->getStore();

        $this->order = new Order();
        $this->order->store = $store;
        $this->order->supplier = $supplier;
        $this->order->createdDate = new DateTimestamp($createdDate);

        return $this;
    }

    /**
     * @param string $productId
     * @param int $quantity
     * @return OrderBuilder
     */
    public function createOrderProduct($productId, $quantity = 1)
    {
        $orderProduct = new OrderProduct();
        $orderProduct->order = $this->order;
        $orderProduct->product = $this->factory->createProductVersion($productId);
        $orderProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $orderProduct->storeProduct = $this->factory->getStoreProduct($this->order->store->id, $productId);

        $this->order->products->add($orderProduct);

        return $this;
    }

    /**
     * @return Order
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $this->persist();
        $this->orderRepository->getDocumentManager()->flush();
        return $this->order;
    }

    /**
     * @return OrderFactory
     * @throws \InvalidArgumentException
     */
    public function persist()
    {
        $this->validator->validate($this->order);
        $this->orderRepository->getDocumentManager()->persist($this->order);
        return $this->factory->order();
    }
}
