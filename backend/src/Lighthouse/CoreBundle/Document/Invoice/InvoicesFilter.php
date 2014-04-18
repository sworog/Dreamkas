<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;

class InvoicesFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $numberOrSupplierInvoiceNumber;

    /**
     * @param string $supplierInvoiceSku
     */
    public function setNumberOrSupplierInvoiceNumber($supplierInvoiceSku)
    {
        $this->numberOrSupplierInvoiceNumber = $supplierInvoiceSku;
    }

    /**
     * @return string
     */
    public function getNumberOrSupplierInvoiceNumber()
    {
        return $this->numberOrSupplierInvoiceNumber;
    }

    /**
     * @return bool
     */
    public function hasNumberOrSupplierInvoiceNumber()
    {
        return null !== $this->numberOrSupplierInvoiceNumber;
    }

    /**
     * @param array $data
     * @return null|void
     */
    public function populate(array $data)
    {
        if (isset($data['numberOrSupplierInvoiceNumber'])) {
            $this->setNumberOrSupplierInvoiceNumber($data['numberOrSupplierInvoiceNumber']);
        }
    }
}
