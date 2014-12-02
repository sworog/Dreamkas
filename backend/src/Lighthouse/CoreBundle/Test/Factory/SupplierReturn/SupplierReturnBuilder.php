<?php

namespace Lighthouse\CoreBundle\Test\Factory\SupplierReturn;

use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnProduct;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierReturnBuilder
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var SupplierReturnRepository
     */
    protected $repository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var SupplierReturn
     */
    protected $supplierReturn;

    /**
     * @param Factory $factory
     * @param SupplierReturnRepository $supplierReturnRepository
     * @param ValidatorInterface $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        Factory $factory,
        SupplierReturnRepository $supplierReturnRepository,
        ValidatorInterface $validator,
        NumericFactory $numericFactory
    ) {
        $this->factory = $factory;
        $this->repository = $supplierReturnRepository;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Store $store
     * @param string $date
     * @param Supplier $supplier
     * @param bool $paid
     * @return SupplierReturnBuilder
     */
    public function createSupplierReturn(Store $store = null, $date = null, Supplier $supplier = null, $paid = false)
    {
        $date = ($date) ?: new \DateTime();

        $store = ($store) ?: $this->factory->store()->getStore();

        $supplier = ($supplier) ?: $this->factory->supplier()->getSupplier();

        $this->supplierReturn = $this->repository->createNew();
        $this->supplierReturn->store = $store;
        $this->supplierReturn->supplier = $supplier;
        $this->supplierReturn->paid = $paid;
        $this->supplierReturn->date = new DateTimestamp($date);

        return $this;
    }

    /**
     * @param $supplierReturnId
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
        Supplier $supplier = null,
        $paid = false
    ) {
        $this->supplierReturn = $this->factory->supplierReturn()->getSupplierReturnById($supplierReturnId);

        if ($store) {
            $this->supplierReturn->store = $store;
        }
        if ($supplier) {
            $this->supplierReturn->supplier = $supplier;
        }
        if ($paid) {
            $this->supplierReturn->paid = $paid;
        }
        if ($date) {
            $this->supplierReturn->date = new DateTimestamp($date);
        }

        return $this;
    }

    /**
     * @param string $supplierReturnId
     */
    public function removeSupplierReturn($supplierReturnId)
    {
        $this->supplierReturn = $this->factory->supplierReturn()->getSupplierReturnById($supplierReturnId);
        $this->repository->getDocumentManager()->remove($this->supplierReturn);
        $this->repository->getDocumentManager()->flush();
    }

    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return SupplierReturnBuilder
     */
    public function createSupplierReturnProduct($productId, $quantity = null, $price = null)
    {
        $quantity = ($quantity) ?: 1;
        $price = ($price) ?: 5.99;

        $supplierReturnProduct = new SupplierReturnProduct();
        $supplierReturnProduct->parent = $this->supplierReturn;
        $supplierReturnProduct->product = $this->factory->createProductVersion($productId);
        $supplierReturnProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $supplierReturnProduct->price = $this->numericFactory->createMoney($price);

        $this->supplierReturn->products->add($supplierReturnProduct);

        return $this;
    }

    /**
     * @param int $index
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return SupplierReturnBuilder
     */
    public function editSupplierReturnProduct($index, $productId, $quantity, $price)
    {
        $supplierReturnProduct = $this->supplierReturn->products[$index];
        $supplierReturnProduct->parent = $this->supplierReturn;
        $supplierReturnProduct->product = $this->factory->createProductVersion($productId);
        $supplierReturnProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $supplierReturnProduct->price = $this->numericFactory->createMoney($price);
        $supplierReturnProduct->calculateTotals();

        return $this;
    }

    /**
     * @return SupplierReturnFactory
     * @throws \InvalidArgumentException
     */
    public function persist()
    {
        $this->validator->validate($this->supplierReturn);
        $this->repository->getDocumentManager()->persist($this->supplierReturn);
        return $this->factory->supplierReturn();
    }

    /**
     * @return SupplierReturn
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $this->persist();
        $this->repository->getDocumentManager()->flush();
        return $this->supplierReturn;
    }
}
