<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Integration\Set10\XmlParser;
use DOMNode;

/**
 * @method readNextElement() GoodElement
 */
class Set10ProductImportXmlParser extends XmlParser
{
    /**
     * @param $name
     * @return bool
     */
    protected function supportsNodeName($name)
    {
        return 'good' == $name;
    }

    /**
     * @param DOMNode $node
     * @return GoodElement
     */
    protected function createElement(DOMNode $node)
    {
        return simplexml_import_dom($node, GoodElement::getClassName());
    }
}
