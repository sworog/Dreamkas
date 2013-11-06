<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Sales;

use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Integration\Set10\Import\Sales\PurchaseElement;
use XMLReader;
use DOMDocument;

class SalesXmlParser
{
    /**
     * @var XMLReader
     */
    protected $xmlReader;

    /**
     * @param string $xmlFilePath
     */
    public function __construct($xmlFilePath)
    {
        $this->createXmlReader($xmlFilePath);
    }

    /**
     * @param string $xmlFilePath
     */
    protected function createXmlReader($xmlFilePath)
    {
        $this->xmlReader = new XMLReader();
        $this->xmlReader->open($xmlFilePath, 'UTF-8', LIBXML_NOERROR | LIBXML_NOWARNING);
    }

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
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     * @return PurchaseElement|false
     */
    public function readNextElement()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && 'purchase' == $this->xmlReader->name) {
                /* FIXME */
                $domNode = @$this->xmlReader->expand();
                if (false === $domNode) {
                    $error = libxml_get_last_error();
                    throw new RuntimeException(sprintf('Failed to parse "purchase" xml node: %s', $error->message));
                }
                $doc = new DOMDocument('1.0', 'UTF-8');
                return simplexml_import_dom($doc->importNode($domNode, true), PurchaseElement::getClassName());
            }
        }
        return false;
    }
}
