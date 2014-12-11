<?php

namespace Lighthouse\CoreBundle\Test\Factory\SupplierReturn;

use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;

class SupplierReturnFactory extends AbstractFactory
{
    /**
     * @param Store $store
     * @param string $date
     * @param Supplier $supplier
     * @param bool $paid
     * @return SupplierReturnBuilder
     */
    public function createSupplierReturn(Store $store = null, $date = null, $supplier = null, $paid = false)
    {
        $builder = new SupplierReturnBuilder(
            $this->factory,
            $this->getSupplierReturnRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createSupplierReturn($store, $date, $supplier, $paid);
    }

    /**
     * @param string $supplierReturnId
     * @param Store $store
     * @param string $date
     * @param Supplier $supplier
     * @param bool $paid
     * @return SupplierReturnBuilder
     */
    public function editSupplierReturn(
        $supplierReturnId,
        Store $store = null,
        $date = null,
        $supplier = null,
        $paid = null
    ) {
        $builder = new SupplierReturnBuilder(
            $this->factory,
            $this->getSupplierReturnRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->editSupplierReturn($supplierReturnId, $store, $date, $supplier, $paid);
    }

    /**
     * @param string $supplierReturnId
     */
    public function removeSupplierReturn($supplierReturnId)
    {
        $builder = new SupplierReturnBuilder(
            $this->factory,
            $this->getSupplierReturnRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        $builder->removeSupplierReturn($supplierReturnId);
    }

    /**
     * @param $id
     * @return SupplierReturn
     */
    public function getSupplierReturnById($id)
    {
        $supplierReturn = $this->getSupplierReturnRepository()->find($id);
        if (null === $supplierReturn) {
            throw new \RuntimeException(sprintf('SupplierReturn id#%s not found', $id));
        }
        return $supplierReturn;
    }

    /**
     * @return SupplierReturnRepository
     */
    public function getSupplierReturnRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.stock_movement.supplier_return');
    }
}
