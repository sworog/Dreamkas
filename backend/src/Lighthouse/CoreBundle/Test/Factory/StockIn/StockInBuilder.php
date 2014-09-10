<?php

namespace Lighthouse\CoreBundle\Test\Factory\StockIn;

use Lighthouse\CoreBundle\Document\StockMovement\StockIn\Product\StockInProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Validator\ValidatorInterface;

class StockInBuilder
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var StockInRepository
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
     * @var StockIn
     */
    protected $stockIn;

    /**
     * @param Factory $factory
     * @param StockInRepository $stockInRepository
     * @param ValidatorInterface $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        Factory $factory,
        StockInRepository $stockInRepository,
        ValidatorInterface $validator,
        NumericFactory $numericFactory
    ) {
        $this->factory = $factory;
        $this->repository = $stockInRepository;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Store $store
     * @param string $date
     * @return StockInBuilder
     */
    public function createStockIn(Store $store = null, $date = null)
    {
        $date = ($date) ?: new \DateTime();

        $store = ($store) ?: $this->factory->store()->getStore();

        $this->stockIn = $this->repository->createNew();
        $this->stockIn->store = $store;
        $this->stockIn->date = new DateTimestamp($date);

        return $this;
    }

    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @internal param string $cause
     * @return StockInBuilder
     */
    public function createStockInProduct($productId, $quantity = null, $price = null)
    {
        $quantity = ($quantity) ?: 1;
        $price = ($price) ?: 5.99;

        $stockInProduct = new StockInProduct();
        $stockInProduct->parent = $this->stockIn;
        $stockInProduct->product = $this->factory->createProductVersion($productId);
        $stockInProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $stockInProduct->price = $this->numericFactory->createMoney($price);

        $this->stockIn->products->add($stockInProduct);

        return $this;
    }

    /**
     * @return StockInFactory
     * @throws \InvalidArgumentException
     */
    public function persist()
    {
        $this->validator->validate($this->stockIn);
        $this->repository->getDocumentManager()->persist($this->stockIn);
        return $this->factory->stockIn();
    }

    /**
     * @return StockIn
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $this->persist();
        $this->repository->getDocumentManager()->flush();
        return $this->stockIn;
    }
}
