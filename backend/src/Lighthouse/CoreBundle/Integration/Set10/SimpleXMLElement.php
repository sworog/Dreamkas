<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

use Lighthouse\CoreBundle\Document\ClassNameable;
use DomNode;

class SimpleXMLElement extends \SimpleXMLElement implements ClassNameable
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

    /**
     * @param $name
     * @param null $value
     * @return \SimpleXMLElement
     */
    protected function setChild($name, $value = null)
    {
        if (isset($this->$name)) {
            $child = $this->$name;
            $this->$name = $value;
        } else {
            $child = $this->addChild($name, $value);
        }

        return $child;
    }

    /**
     * @param SimpleXMLElement $element
     */
    protected function addSimpleXmlElement(SimpleXMLElement $element)
    {
        $domDoc = $this->toDomNode()->ownerDocument;
        $elementNode = $domDoc->importNode($element->toDomNode(), true);
        $domDoc->getElementsByTagName($this->getName())->item(0)->appendChild($elementNode);
    }

    /**
     * @return \DOMElement
     */
    public function toDomNode()
    {
        return dom_import_simplexml($this);
    }
}
