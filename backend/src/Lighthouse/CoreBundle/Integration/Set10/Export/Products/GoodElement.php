<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Export\Products;

use Lighthouse\CoreBundle\Integration\Set10\SimpleXMLElement;

class GoodElement extends SimpleXMLElement
{
    /**
     * @return GoodElement
     */
    public static function create()
    {
        return new static('<?xml version="1.0" encoding="UTF-8"?><good/>');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->addChild('name', $name);
        return $this;
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
     * @param string $marking
     * @return $this
     */
    public function setMarking($marking)
    {
        $this->addAttribute('marking-of-the-good', $marking);
        return $this;
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
     * @param int $vat
     * @return $this
     */
    public function setVat($vat)
    {
        $this->addChild('vat', $vat);
        return $this;
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
            $childElement = $groupElement->addChild($tag);
            $childElement->addAttribute('id', $id);
            $childElement->addChild('name', $name);

            $tag = 'parent-group';
            $groupElement = $childElement;
        }
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
}
