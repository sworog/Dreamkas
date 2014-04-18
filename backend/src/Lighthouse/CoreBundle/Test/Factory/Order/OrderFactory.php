<?php

namespace Lighthouse\CoreBundle\Test\Factory\Order;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;

class OrderFactory extends AbstractFactory
{
    /**
     * @param Store $store
     * @param Supplier $supplier
     * @param string $createdDate
     * @return OrderBuilder
     */
    public function createOrder(Store $store = null, Supplier $supplier = null, $createdDate = null)
    {
        $builder = new OrderBuilder(
            $this->factory,
            $this->getOrderRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createOrder($store, $supplier, $createdDate);
    }

    /**
     * @param string $orderId
     * @return Order
     * @throws \RuntimeException
     */
    public function getOrderById($orderId)
    {
        $store = $this->getOrderRepository()->find($orderId);
        if (null === $store) {
            throw new \RuntimeException(sprintf('Order id#%s not found', $orderId));
        }
        return $store;
    }

    /**
     * @return OrderRepository
     */
    protected function getOrderRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.order');
    }
}
