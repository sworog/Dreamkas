<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\DiExtraBundle\Annotation as DI;
use XMLReader;
use DOMDocument;

/**
 * @DI\Service("lighthouse.core.integration.set10.import.products.xml_parser")
 */
class Set10ProductImportXmlParser
{
    /**
     * @var XMLReader
     */
    protected $xmlReader;

    /**
     * @param $xmlFilePath
     */
    public function setXmlFilePath($xmlFilePath)
    {
        $this->createXmlReader($xmlFilePath);
    }

    /**
     * @param string $xmlFilePath
     */
    protected function createXmlReader($xmlFilePath)
    {
        $this->xmlReader = new XMLReader();
        $this->xmlReader->open($xmlFilePath, 'UTF-8');
    }

    /**
     * @return GoodElement|boolean
     */
    public function readNextNode()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && 'good' == $this->xmlReader->name) {
                $domNode = $this->xmlReader->expand();
                $doc = new DOMDocument('1.0', 'UTF-8');
                return simplexml_import_dom($doc->importNode($domNode, true), GoodElement::getClassName());
            }
        }
        return false;
    }
}
