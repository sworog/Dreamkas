<?php

namespace Lighthouse\CoreBundle\Integration\Set10\ImportSales;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use XMLReader;
use DOMDocument;

class ImportSalesXmlParser
{
    /**
     * @var XMLReader
     */
    protected $xmlReader;

    /**
     * @var MoneyModelTransformer
     */
    protected $moneyModelTransformer;

    /**
     * @param MoneyModelTransformer $moneyModelTransformer
     *
     * @DI\InjectParams({
     *      "moneyModelTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model"),
     * })
     */
    public function __construct(MoneyModelTransformer $moneyModelTransformer)
    {
        $this->moneyModelTransformer = $moneyModelTransformer;
    }

    /**
     * @param string $xmlFilePath
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
     * @return PurchaseElement|false
     */
    public function readNextElement()
    {
        while ($this->xmlReader->read()) {
            if (XMLReader::ELEMENT === $this->xmlReader->nodeType && 'purchase' == $this->xmlReader->name) {
                $domNode = $this->xmlReader->expand();
                $doc = new DOMDocument('1.0', 'UTF-8');
                return simplexml_import_dom($doc->importNode($domNode, true), PurchaseElement::getClassName());
            }
        }
        return false;
    }
}
