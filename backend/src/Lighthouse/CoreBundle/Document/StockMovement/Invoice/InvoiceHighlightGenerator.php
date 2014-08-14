<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Invoice;


use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;

class InvoiceHighlightGenerator implements MetaGeneratorInterface
{
    /**
     * @var InvoiceFilter
     */
    protected $filter;

    /**
     * @param InvoiceFilter $filter
     */
    public function __construct(InvoiceFilter $filter)
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
        if ($element->number == $this->filter->getNumberOrSupplierInvoiceNumber()) {
            $highlights['number'] = true;
        }
        if ($element->supplierInvoiceNumber == $this->filter->getNumberOrSupplierInvoiceNumber()) {
            $highlights['supplierInvoiceNumber'] = true;
        }
        return array('highlights' => $highlights);
    }
}
