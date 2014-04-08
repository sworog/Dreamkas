<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;

class InvoiceFactory extends AbstractFactory
{
    /**
     * @param array $data
     * @param string $storeId
     * @param string $invoiceId
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Lighthouse\CoreBundle\Exception\ValidationFailedException
     * @return Invoice
     */
    public function createInvoice(array $data, $storeId, $invoiceId = null)
    {
        $invoice = ($invoiceId) ? $this->getInvoiceById($invoiceId) : new Invoice();

        $invoiceData = $data + array(
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'includesVAT' => true,
        );

        $invoice->supplier = $this->factory->supplier()->getSupplier($invoiceData['supplier']);
        $invoice->acceptanceDate = new \DateTime($invoiceData['acceptanceDate']);
        $invoice->accepter = $invoiceData['accepter'];
        $invoice->legalEntity = $invoiceData['legalEntity'];
        $invoice->supplierInvoiceSku = $invoiceData['supplierInvoiceSku'];
        $invoice->includesVAT = $invoiceData['includesVAT'];

        $invoice->store = $this->factory->store()->getStoreById($storeId);

        $this->getValidator()->validate($invoice);

        $this->getDocumentManager()->persist($invoice);
        $this->getDocumentManager()->flush($invoice);

        return $invoice;
    }

    /**
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $invoiceId
     * @return InvoiceProduct
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Lighthouse\CoreBundle\Exception\ValidationFailedException
     */
    public function createInvoiceProduct($invoiceId, $productId, $quantity, $price, $id = null)
    {
        $invoiceProduct = ($id) ? $this->getInvoiceProductById($id) : new InvoiceProduct();

        $invoiceProduct->quantity = $this->getNumericFactory()->createQuantity($quantity);
        $invoiceProduct->priceEntered = $this->getNumericFactory()->createMoney($price);
        $invoiceProduct->product = $this->factory->createProductVersion($productId);
        $invoiceProduct->invoice = $this->getInvoiceById($invoiceId);

        $invoiceProduct->calculatePrices();

        $this->getValidator()->validate($invoiceProduct);

        $this->getDocumentManager()->persist($invoiceProduct);
        $this->getDocumentManager()->flush($invoiceProduct);

        return $invoiceProduct;
    }

    /**
     * @param string $id
     * @return Invoice
     */
    public function getInvoiceById($id)
    {
        $invoice = $this->getInvoiceRepository()->find($id);
        if (null === $invoice) {
            throw new \RuntimeException(sprintf('Invoice id#%s not found', $id));
        }
        return $invoice;
    }

    /**
     * @param string $id
     * @return Invoice
     */
    public function getInvoiceProductById($id)
    {
        $invoiceProduct = $this->getInvoiceProductRepository()->find($id);
        if (null === $invoiceProduct) {
            throw new \RuntimeException(sprintf('InvoiceProduct id#%s not found', $id));
        }
        return $invoiceProduct;
    }

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->container->get('lighthouse.core.types.numeric.factory');
    }
    /**
     * @return InvoiceRepository
     */
    protected function getInvoiceRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.invoice');
    }

    /**
     * @return InvoiceProductRepository
     */
    protected function getInvoiceProductRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.invoice_product');
    }

    /**
     * @return ExceptionalValidator
     */
    protected function getValidator()
    {
        return $this->container->get('lighthouse.core.validator');
    }
}
