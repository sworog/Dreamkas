<?php

namespace Lighthouse\CoreBundle\Test\Factory\Invoice;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class InvoiceFactory extends AbstractFactory
{
    /**
     * @param array $data
     * @param string $storeId
     * @param string $supplierId
     * @param null $orderId
     * @return InvoiceBuilder
     */
    public function createInvoice(array $data, $storeId = null, $supplierId = null, $orderId = null)
    {
        $builder = new InvoiceBuilder(
            $this->factory,
            $this->getInvoiceRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createInvoice($data, $storeId, $supplierId, $orderId);
    }

    /**
     * @param string $invoiceId
     * @param array $data
     * @param string $storeId
     * @param string $supplierId
     * @param null $orderId
     * @return InvoiceBuilder
     */
    public function editInvoice($invoiceId, array $data, $storeId = null, $supplierId = null, $orderId = null)
    {
        $builder = new InvoiceBuilder(
            $this->factory,
            $this->getInvoiceRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->editInvoice($invoiceId, $data, $storeId, $supplierId, $orderId);
    }

    /**
     * @param Invoice $invoice
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $id invoice product id to update
     * @throws \RuntimeException
     * @internal param string $invoiceId
     * @return InvoiceProduct
     */
    public function createInvoiceProduct(Invoice $invoice, $productId, $quantity, $price, $id = null)
    {
        $invoiceProduct = ($id) ? $this->getInvoiceProductById($id) : new InvoiceProduct();

        $invoiceProduct->quantity = $this->getNumericFactory()->createQuantity($quantity);
        $invoiceProduct->priceEntered = $this->getNumericFactory()->createMoney($price);
        $invoiceProduct->product = $this->factory->createProductVersion($productId);
        $invoiceProduct->invoice = $invoice;

        $invoice->products[] = $invoiceProduct;

        $invoiceProduct->calculatePrices();

        return $invoiceProduct;
    }

    /**
     * @param string $id
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function deleteInvoiceProduct($id)
    {
        $invoiceProduct = $this->getInvoiceProductById($id);
        $this->getDocumentManager()->remove($invoiceProduct);
        $this->getDocumentManager()->flush($invoiceProduct);
    }

    /**
     * @param string $id
     * @throws \RuntimeException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \Doctrine\ODM\MongoDB\LockException
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
     * @throws \RuntimeException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \Doctrine\ODM\MongoDB\LockException
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
    public function getNumericFactory()
    {
        return $this->container->get('lighthouse.core.types.numeric.factory');
    }
    /**
     * @return InvoiceRepository
     */
    public function getInvoiceRepository()
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
}
