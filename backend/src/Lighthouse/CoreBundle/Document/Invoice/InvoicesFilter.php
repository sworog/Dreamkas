<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;

class InvoicesFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $skuOrSupplierInvoiceSku;

    /**
     * @param string $supplierInvoiceSku
     */
    public function setSkuOrSupplierInvoiceSku($supplierInvoiceSku)
    {
        $this->skuOrSupplierInvoiceSku = $supplierInvoiceSku;
    }

    /**
     * @return string
     */
    public function getSkuOrSupplierInvoiceSku()
    {
        return $this->skuOrSupplierInvoiceSku;
    }

    /**
     * @return bool
     */
    public function hasSkuOrSupplierInvoiceSku()
    {
        return null !== $this->skuOrSupplierInvoiceSku;
    }

    /**
     * @param array $data
     * @return null|void
     */
    public function populate(array $data)
    {
        if (isset($data['skuOrSupplierInvoiceSku'])) {
            $this->setSkuOrSupplierInvoiceSku($data['skuOrSupplierInvoiceSku']);
        }
    }
}
