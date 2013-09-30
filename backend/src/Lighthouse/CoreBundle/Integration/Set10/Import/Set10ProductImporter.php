<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import;

use XMLReader;
use SimpleXMLElement;

class Set10ProductImporter
{
    /**
     * @var XMLReader
     */
    protected $xmlReader;

    /**
     * @param $xmlFilePath
     */
    public function __construct($xmlFilePath)
    {
        $this->xmlReader = new XMLReader();
        $this->xmlReader->open($xmlFilePath, 'UTF-8');
    }

    /**
     * @return SimpleXMLElement
     */
    public function readNextNode()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && 'good' == $this->xmlReader->name) {
                $domNode = $this->xmlReader->expand();
                $doc = new \DOMDocument('1.0', 'UTF-8');
                return simplexml_import_dom($doc->importNode($domNode, true));
            }
        }
    }
}
