<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\StockIn;

use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;

class StockInHighlightGenerator implements MetaGeneratorInterface
{
    /**
     * @var StockInFilter
     */
    protected $filter;

    /**
     * @param StockInFilter $filter
     */
    public function __construct(StockInFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Return meta array for element
     * @param StockIn $element
     * @return array
     */
    public function getMetaForElement($element)
    {
        $highlights = array();
        if ($element->number == $this->filter->getNumber()) {
            $highlights['number'] = true;
        }

        return array('highlights' => $highlights);
    }
}
