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
     * @param string $number
     * @return WriteOffBuilder
     */
    public function createWriteOff(Store $store = null, $date = null, $number = null)
    {
        $date = ($date) ?: new \DateTime();
        $number = ($number) ?: '10001';

        $store = ($store) ?: $this->factory->store()->getStore();

        $this->writeOff = new WriteOff();
        $this->writeOff->store = $store;
        $this->writeOff->date = new DateTimestamp($date);
        $this->writeOff->number = $number;

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

        $orderProduct = new WriteOffProduct();
        $orderProduct->writeOff = $this->writeOff;
        $orderProduct->product = $this->factory->createProductVersion($productId);
        $orderProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $orderProduct->price = $this->numericFactory->createMoney($price);
        $orderProduct->cause = $cause;

        $this->writeOff->products->add($orderProduct);

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
