<?php

namespace Lighthouse\CoreBundle\Test\Factory\WriteOff;

use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Validator\ValidatorInterface;

class WriteOffBuilder
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var WriteOffRepository
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
     * @var WriteOff
     */
    protected $writeOff;

    /**
     * @param Factory $factory
     * @param WriteOffRepository $writeOffRepository
     * @param ValidatorInterface $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        Factory $factory,
        WriteOffRepository $writeOffRepository,
        ValidatorInterface $validator,
        NumericFactory $numericFactory
    ) {
        $this->factory = $factory;
        $this->repository = $writeOffRepository;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Store $store
     * @param string $date
     * @return WriteOffBuilder
     */
    public function createWriteOff(Store $store = null, $date = null)
    {
        $date = ($date) ?: new \DateTime();

        $store = ($store) ?: $this->factory->store()->getStore();

        $this->writeOff = $this->repository->createNew();
        $this->writeOff->store = $store;
        $this->writeOff->date = new DateTimestamp($date);

        return $this;
    }

    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $cause
     * @return WriteOffBuilder
     */
    public function createWriteOffProduct($productId, $quantity = null, $price = null, $cause = null)
    {
        $quantity = ($quantity) ?: 1;
        $price = ($price) ?: 5.99;
        $cause = ($cause) ?: 'Порча';

        $writeOffProduct = new WriteOffProduct();
        $writeOffProduct->parent = $this->writeOff;
        $writeOffProduct->product = $this->factory->createProductVersion($productId);
        $writeOffProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $writeOffProduct->price = $this->numericFactory->createMoney($price);
        $writeOffProduct->cause = $cause;

        $this->writeOff->products->add($writeOffProduct);

        return $this;
    }

    /**
     * @return WriteOffFactory
     * @throws \InvalidArgumentException
     */
    public function persist()
    {
        $this->validator->validate($this->writeOff);
        $this->repository->getDocumentManager()->persist($this->writeOff);
        return $this->factory->writeOff();
    }

    /**
     * @return WriteOff
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $this->persist();
        $this->repository->getDocumentManager()->flush();
        return $this->writeOff;
    }
}
