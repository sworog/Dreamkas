<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

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
     * @return string
     */
    public function asXmlWithoutHeader()
    {
        $xml = $this->asXML();
        return substr($xml, strpos($xml, '?>') + 3);
    }
}
