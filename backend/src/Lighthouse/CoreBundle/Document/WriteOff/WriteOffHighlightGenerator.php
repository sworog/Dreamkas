<?php

namespace Lighthouse\CoreBundle\Document\WriteOff;

use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;

class WriteOffHighlightGenerator implements MetaGeneratorInterface
{
    /**
     * @var WriteOffsFilter
     */
    protected $filter;

    public function __construct(WriteOffsFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Return meta array for element
     * @param WriteOff $element
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
