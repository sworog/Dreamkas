<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Document\Supplier\SupplierRepository;

class SupplierFactory extends AbstractFactory
{
    const DEFAULT_SUPPLIER_NAME = 'default';

    /**
     * @var array name => id
     */
    protected $supplierNames = array();

    /**
     * @param string $name
     * @return Supplier
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \InvalidArgumentException
     */
    public function getSupplier($name = self::DEFAULT_SUPPLIER_NAME)
    {
        if (!isset($this->supplierNames[$name])) {
            $this->createSupplier($name);
        }
        return $this->getSupplierById($this->supplierNames[$name]);
    }

    /**
     * @param string $id
     * @return Supplier
     * @throws \RuntimeException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function getSupplierById($id)
    {
        $supplier = $this->getSupplierRepository()->find($id);
        if (null === $supplier) {
            throw new \RuntimeException(sprintf('Supplier id#%s not found', $id));
        }
        return $supplier;
    }

    /**
     * @param string $name
     * @return Supplier
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \InvalidArgumentException
     */
    public function createSupplier($name = self::DEFAULT_SUPPLIER_NAME)
    {
        $supplier = new Supplier();
        $supplier->name = $name;

        $this->getDocumentManager()->persist($supplier);
        $this->getDocumentManager()->flush($supplier);

        $this->supplierNames[$supplier->name] = $supplier->id;

        return $supplier;
    }

    /**
     * @return SupplierRepository
     */
    protected function getSupplierRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.supplier');
    }
}
