<?php

namespace Lighthouse\IntegrationBundle\Set10\Import\Sales;

use Lighthouse\CoreBundle\Document\ClassNameable;
use SimpleXMLElement;

class PositionElement implements ClassNameable
{
    /**
     * @var SimpleXMLElement
     */
    protected $xml;

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    /**
     * @return string
     */
    public function getCost()
    {
        return (string) $this->xml['cost'];
    }

    /**
     * @return string
     */
    public function getCostWithDiscount()
    {
        return (string) $this->xml['costWithDiscount'];
    }

    /**
     * @return string
     */
    public function getCount()
    {
        return (string) $this->xml['count'];
    }

    /**
     * @return string
     */
    public function getGoodsCode()
    {
        return (string) $this->xml['goodsCode'];
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return (string) $this->xml['amount'];
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}
