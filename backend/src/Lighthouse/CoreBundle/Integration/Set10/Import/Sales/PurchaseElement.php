<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Sales;

use Lighthouse\CoreBundle\Integration\Set10\SimpleXMLElement;
use DateTime;
use DomNode;

class PurchaseElement extends SimpleXMLElement
{
    /**
     * @param DomNode $dom
     * @return \SimpleXMLElement
     */
    public static function createByDom(DomNode $dom)
    {
        return simplexml_import_dom($dom, static::getClassName());
    }

    /**
     * @return string
     */
    public function getSaleTime()
    {
        return (string) $this['saletime'];

    }

    /**
     * @return DateTime
     */
    public function getSaleDateTime()
    {
        return DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $this->getSaleTime());
    }

    /**
     * @return string
     */
    public function getShop()
    {
        return (string) $this['shop'];
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return (string) $this['amount'];
    }

    /**
     * @return bool
     */
    public function getOperationType()
    {
        switch ((string) $this['operationType']) {
            case 'true':
                return true;
            case 'false':
                return false;
            default:
                return null;
        }
    }

    /**
     * @return PositionElement[]
     */
    public function getPositions()
    {
        $positions = array();
        foreach ($this->positions->position as $position) {
            $positions[] = new PositionElement($position);
        }
        return $positions;
    }
}
