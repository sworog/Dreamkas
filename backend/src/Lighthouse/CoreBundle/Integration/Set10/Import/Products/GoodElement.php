<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Integration\Set10\SimpleXMLElement;
use DOMNode;

class GoodElement extends SimpleXMLElement
{
    const PRODUCT_PIECE_ENTITY = 'ProductPieceEntity';
    const PRODUCT_WEIGHT_ENTITY = 'ProductWeightEntity';
    const PRODUCT_SPIRITS_ENTITY = 'ProductSpiritsEntity';

    const PLUGIN_PROPERTY_NAME_ON_SCALE_SCREEN = 'name-on-scale-screen';
    const PLUGIN_PROPERTY_DESCRIPTION_ON_SCALE_SCREEN = 'description-on-scale-screen';
    const PLUGIN_PROPERTY_COMPOSITION = 'composition';
    const PLUGIN_PROPERTY_FOOD_VALUE = 'food-value';
    const PLUGIN_PROPERTY_GOOD_FOR_HOURS = 'good-for-hours';
    const PLUGIN_PROPERTY_ALCOHOLIC_CONTENT_PERCENTAGE = 'alcoholic-content-percentage';
    const PLUGIN_PROPERTY_VOLUME = 'volume';

    /**
     * @return GoodElement
     */
    public static function create()
    {
        return new static('<?xml version="1.0" encoding="UTF-8"?><good/>');
    }

    /**
     * @param DOMNode $dom
     * @return \SimpleXMLElement
     */
    public static function createByDom(DomNode $dom)
    {
        return simplexml_import_dom($dom, static::getClassName());
    }

    /**
     * @return string
     */
    public function getGoodName()
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setGoodName($name)
    {
        $this->addChild('name', $name);
        return $this;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return (int) $this->vat;
    }

    /**
     * @param int $vat
     * @return $this
     */
    public function setVat($vat)
    {
        $this->addChild('vat', $vat);
        return $this;
    }

    /**
     * @return string
     */
    public function getMarkingOfTheGood()
    {
        return (string) $this['marking-of-the-good'];
    }

    /**
     * @param string $marking
     * @return $this
     */
    public function setMarkingOfTheGood($marking)
    {
        $this->addAttribute('marking-of-the-good', $marking);
        return $this;
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
        return null;
    }

    /**
     * @param string $barcode
     * @return $this
     */
    public function setBarcode($barcode)
    {
        $barcodeElement = $this->addChild('bar-code');
        $barcodeElement->addAttribute('code', $barcode);
        $barcodeElement->addChild('count', 1);
        $barcodeElement->addChild('default-code', 'true');
        return $this;
    }

    /**
     * @return string
     */
    public function getManufacturerName()
    {
        return (string) $this->manufacturer->name;
    }

    /**
     * @param string $id
     * @param string $name
     * @return $this
     */
    public function setMeasureType($id, $name)
    {
        $unitsElement = $this->addChild('measure-type');
        $unitsElement->addAttribute('id', $id);
        $unitsElement->addChild('name', $name);
        return $this;
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
        return null;
    }

    /**
     * @param float $price
     * @param int $priceNumber
     * @param int $departmentNumber
     * @param string $departmentName
     * @return $this
     */
    public function setPrice($price, $priceNumber = null, $departmentNumber = null, $departmentName = null)
    {
        $priceElement = $this->addChild('price-entry');
        $priceElement->addAttribute('price', $price);
        $priceElement->addChild('number', ($priceNumber) ?: 1);
        /** Залипон, что б касса съедала цену */
        $priceDepartmentElement = $priceElement->addChild('department');
        $priceDepartmentElement->addAttribute('number', ($departmentNumber) ?: 1);
        $priceDepartmentElement->addChild('name', ($departmentName) ?: 1);
        return $this;
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
            while (isset($parentGroup->{'parent-group'})) {
                $parentGroup = $parentGroup->{'parent-group'};
                array_unshift(
                    $groups,
                    array(
                        'id' => (string) $parentGroup['id'],
                        'name' => (string) $parentGroup->name,
                    )
                );
            }
        }
        return $groups;
    }

    /**
     * @param array $groups
     * @return $this
     */
    public function setGroups(array $groups)
    {
        $tag = 'group';
        $groupElement = $this;
        foreach ($groups as $id => $name) {
            /* @var GoodElement $childElement */
            $childElement = $groupElement->addChild($tag);
            $childElement->addAttribute('id', $id);
            $childElement->addChild('name', $name);

            $tag = 'parent-group';
            $groupElement = $childElement;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getProductType()
    {
        return (string) $this->{'product-type'};
    }

    /**
     * @param string $productType
     * @return $this
     */
    public function setProductType($productType)
    {
        $this->addChild('product-type', $productType);
        return $this;
    }

    /**
     * @param array $shopIndices
     * @return $this
     */
    public function setShopIndices(array $shopIndices)
    {
        $this->addChild('shop-indices', implode(' ', $shopIndices));
        return $this;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setPluginProperty($key, $value)
    {
        $pluginElement = $this->addChild('plugin-property');
        $pluginElement->addAttribute('key', $key);
        $pluginElement->addAttribute('value', $value);
        return $this;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getPluginProperty($key)
    {
        foreach ($this->{'plugin-property'} as $pluginProperty) {
            if ((string) $pluginProperty['key'] == $key) {
                return (string) $pluginProperty['value'];
            }
        }
        return null;
    }
}
