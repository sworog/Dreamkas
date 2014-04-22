<?php

namespace Lighthouse\CoreBundle\Test\Factory\Invoice;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
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
     * @param string $storeId
     * @param string $supplierId
     * @param string $orderId
     * @throws \RuntimeException
     * @return InvoiceBuilder
     */
    public function createInvoice(array $data, $storeId = null, $supplierId = null, $orderId = null)
    {
        $this->invoice = $this->invoiceRepository->createNew();

        $invoiceData = $data + array(
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
            'includesVAT' => true,
        );

        $this->populateInvoice($this->invoice, $invoiceData);

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

        if ($orderId) {
            $this->invoice->order = $this->factory->order()->getOrderById($orderId);
        }

        return $this;
    }

    /**
     * @param Invoice $invoice
     * @param array $invoiceData
     * @throws \Exception
     */
    protected function populateInvoice(Invoice $invoice, array $invoiceData)
    {
        if (isset($invoiceData['acceptanceDate']) && !$invoiceData['acceptanceDate'] instanceof \DateTime) {
            $invoiceData['acceptanceDate'] = new \DateTime($invoiceData['acceptanceDate']);
        }

        foreach ($invoiceData as $field => $value) {
            $invoice->__set($field, $value);
        }
    }

    /**
     * @param $invoiceId
     * @param array $data
     * @param null $storeId
     * @param null $supplierId
     * @param null $orderId
     * @return InvoiceBuilder
     */
    public function editInvoice($invoiceId, array $data, $storeId = null, $supplierId = null, $orderId = null)
    {
        $this->invoice = $this->factory->invoice()->getInvoiceById($invoiceId);
        $this->populateInvoice($this->invoice, $data);

        if ($supplierId) {
            $this->invoice->supplier = $this->factory->supplier()->getSupplierById($supplierId);
        }

        if ($storeId) {
            $this->invoice->store = $this->factory->store()->getStoreById($storeId);
        }

        if ($orderId) {
            $this->invoice->order = $this->factory->order()->getOrderById($orderId);
        }

        return $this;
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
