<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

use Lighthouse\CoreBundle\Exception\RuntimeException;
use XMLReader;
use DOMNode;
use DOMDocument;

abstract class XmlParser
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
    public function createXmlReader($xmlFilePath)
    {
        $this->xmlReader = new XMLReader();
        $this->xmlReader->open($xmlFilePath, 'UTF-8', LIBXML_NOERROR | LIBXML_NOWARNING);
    }

    /**
     * @throws RuntimeException
     * @return SimpleXMLElement|false
     */
    public function readNextElement()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && $this->supportsNodeName($this->xmlReader->name)) {
                /* FIXME */
                $domNode = @$this->xmlReader->expand();
                if (false === $domNode) {
                    $error = libxml_get_last_error();
                    throw new RuntimeException(
                        sprintf(
                            'Failed to parse node \'%s\': %s',
                            $this->xmlReader->name,
                            $error->message
                        )
                    );
                }
                $doc = new DOMDocument('1.0', 'UTF-8');
                return $this->createElement($doc->importNode($domNode, true));
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    abstract protected function supportsNodeName($name);

    /**
     * @param DOMNode $node
     * @return SimpleXMLElement
     */
    abstract protected function createElement(DOMNode $node);
}
