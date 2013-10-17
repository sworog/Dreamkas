<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;

class InvoiceHighlightGenerator implements MetaGeneratorInterface
{
    /**
     * @var InvoicesFilter
     */
    protected $filter;

    /**
     * @param InvoicesFilter $filter
     */
    public function __construct(InvoicesFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Return meta array for element
     * @param Invoice $element
     * @return array
     */
    public function getMetaForElement($element)
    {
        $highlights = array();
        if ($element->sku == $this->filter->getSkuOrSupplierInvoiceSku()) {
            $highlights['sku'] = true;
        }
        if ($element->supplierInvoiceSku == $this->filter->getSkuOrSupplierInvoiceSku()) {
            $highlights['supplierInvoiceSku'] = true;
        }
        return array('highlights' => $highlights);
    }
}
