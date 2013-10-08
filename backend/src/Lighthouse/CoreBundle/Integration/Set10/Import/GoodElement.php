<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import;

use Lighthouse\CoreBundle\Document\Product\Product;
use SimpleXMLElement;

class GoodElement extends SimpleXMLElement
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
    public function getGoodName()
    {
        return (string) $this->name;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return (int) $this->vat;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return (string) $this['marking-of-the-good'];
    }

    /**
     * @return string
     */
    public function getBarcode()
    {
        foreach ($this->{'bar-code'} as $barCode) {
            if ('true' == (string) $barCode->{'default-code'}) {
                return (string) $barCode['code'];
            }
        }
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return (string) $this->manufacturer->name;
    }

    /**
     * @return string
     */
    public function getUnits()
    {
        $measureType = (string) $this->{'measure-type'}->name;
        switch (true) {
            case preg_match('/^кг\.?$/ui', $measureType):
                return Product::UNITS_KG;
            case preg_match('/^шт\.?$/ui', $measureType):
                return Product::UNITS_UNIT;
            default:
                return null;
        }
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        foreach ($this->{'price-entry'} as $price) {
            if ('1' == $price->number) {
                return (string) $price['price'];
            }
        }
    }

    /**
     * @return array id => name
     */
    public function getGroups()
    {
        $groups = array();
        if (isset($this->group)) {
            array_unshift(
                $groups,
                array(
                    'id' => (string) $this->group['id'],
                    'name' => (string) $this->group->name,
                )
            );
            $parentGroup = $this->group;
            do {
                $parentGroup = $parentGroup->{'parent-group'};
                array_unshift(
                    $groups,
                    array(
                        'id' => (string) $parentGroup['id'],
                        'name' => (string) $parentGroup->name,
                    )
                );
            } while (isset($parentGroup->{'parent-group'}));
        }
        return $groups;
    }
}
