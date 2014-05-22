<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

use DomNode;

class SimpleXMLElement extends \SimpleXMLElement
{
    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }

    /**
     * @param DOMNode $node
     * @return SimpleXMLElement|static
     */
    public static function createByDom(DomNode $node)
    {
        return simplexml_import_dom($node, static::getClassName());
    }

    /**
     * @param \SimpleXMLElement $sxe
     * @return SimpleXMLElement|static
     */
    public static function createBySimpleXml(\SimpleXMLElement $sxe)
    {
        $node = dom_import_simplexml($sxe);
        return static::createByDom($node);
    }

    /**
     * @return string
     */
    public function asXmlWithoutHeader()
    {
        $xml = $this->asXML();
        return substr($xml, strpos($xml, '?>') + 3);
    }
}
