<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Sales;

use Lighthouse\CoreBundle\Integration\Set10\XmlParser;
use XMLReader;
use DOMNode;

/**
 * @method PurchaseElement readNextElement()
 */
class SalesXmlParser extends XmlParser
{
    /**
     * Should be called prior to nodes reading
     * @return int|null
     */
    public function readPurchasesCount()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && 'purchases' == $this->xmlReader->name) {
                $count = $this->xmlReader->getAttribute('count');
                return (int) $count;
            }
        }
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function supportsNodeName($name)
    {
        return 'purchase' == $name;
    }

    /**
     * @param DOMNode $node
     * @return PurchaseElement
     */
    protected function createElement(DOMNode $node)
    {
        return simplexml_import_dom($node, PurchaseElement::getClassName());
    }
}
