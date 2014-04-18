<?php

namespace Lighthouse\CoreBundle\Test\Factory\Invoice;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ValidatorInterface;

class InvoiceBuilder
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @param Factory $factory
     * @param InvoiceRepository $invoiceRepository
     * @param ValidatorInterface $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        Factory $factory,
        InvoiceRepository $invoiceRepository,
        ValidatorInterface $validator,
        NumericFactory $numericFactory
    ) {
        $this->factory = $factory;
        $this->invoiceRepository = $invoiceRepository;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param array $data
     * @param null $storeId
     * @param null $supplierId
     * @throws \RuntimeException
     * @return InvoiceBuilder
     */
    public function createInvoice(array $data, $storeId = null, $supplierId = null)
    {
        $this->invoice = $this->invoiceRepository->createNew();

        $invoiceData = $data + array(
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
            'includesVAT' => true,
        );

        if ($supplierId) {
            $this->invoice->supplier = $this->factory->supplier()->getSupplierById($supplierId);
        } else {
            $this->invoice->supplier = $this->factory->supplier()->getSupplier();
        }

        if ($storeId) {
            $this->invoice->store = $this->factory->store()->getStoreById($storeId);
        } else {
            $this->invoice->store = $this->factory->store()->getStore();
        }

        $this->invoice->acceptanceDate = new \DateTime($invoiceData['acceptanceDate']);
        $this->invoice->accepter = $invoiceData['accepter'];
        $this->invoice->legalEntity = $invoiceData['legalEntity'];
        $this->invoice->supplierInvoiceNumber = $invoiceData['supplierInvoiceNumber'];
        $this->invoice->includesVAT = $invoiceData['includesVAT'];

        return $this;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param $productId
     * @param $quantity
     * @param $price
     * @return $this
     */
    public function createInvoiceProduct($productId, $quantity = null, $price = null)
    {
        $quantity = ($quantity) ?: '10';
        $price = ($price) ?: '5.99';
        $invoiceProduct = new InvoiceProduct();

        $invoiceProduct->quantity = $this->numericFactory->createQuantity($quantity);
        $invoiceProduct->priceEntered = $this->numericFactory->createMoney($price);
        $invoiceProduct->product = $this->factory->createProductVersion($productId);
        $invoiceProduct->invoice = $this->invoice;

        $this->invoice->products[] = $invoiceProduct;

        $invoiceProduct->calculatePrices();

        return $this;
    }

    /**
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $this->persist();
        $this->invoiceRepository->getDocumentManager()->flush();
        return $this->invoice;
    }

    /**
     * @return InvoiceFactory
     * @throws \InvalidArgumentException
     */
    public function persist()
    {
        $this->validator->validate($this->invoice);
        $this->invoiceRepository->getDocumentManager()->persist($this->invoice);
        return $this->factory->invoice();
    }
}
