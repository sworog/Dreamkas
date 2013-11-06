<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Sales;

use SimpleXMLElement;

class PositionElement
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
}
