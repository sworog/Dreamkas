<?php

namespace Lighthouse\CoreBundle\Test\Factory\Invoice;

use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository;
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
    public function editInvoice($invoiceId, array $data = array(), $storeId = null, $supplierId = null, $orderId = null)
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
     * @param string $invoiceId
     */
    public function removeInvoice($invoiceId)
    {
        $builder = new InvoiceBuilder(
            $this->factory,
            $this->getInvoiceRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );

        $builder->removeInvoice($invoiceId);
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
        return $this->container->get('lighthouse.core.document.repository.stock_movement.invoice');
    }

    /**
     * @return StockMovementProductRepository
     */
    protected function getInvoiceProductRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.stock_movement.invoice_product');
    }
}
