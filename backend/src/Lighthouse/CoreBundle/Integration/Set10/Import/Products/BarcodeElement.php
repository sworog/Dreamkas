<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Integration\Set10\SimpleXMLElement;

/**
 * @method static BarcodeElement createBySimpleXml()
 */
class BarcodeElement extends SimpleXMLElement
{
    /**
     * @return BarcodeElement
     */
    public static function create()
    {
        return new static('<?xml version="1.0" encoding="UTF-8"?><bar-code/>');
    }

    /**
     * @return bool
     */
    public function isDefaultCode()
    {
        return 'true' === $this->getDefaultCode();
    }

    /**
     * @return string
     */
    public function getDefaultCode()
    {
        return (string) $this->{'default-code'};
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setDefaultCode($flag = true)
    {
        $this->setChild('default-code', $flag ? 'true' : 'false');
        return $this;
    }

    /**
     * @return string
     */
    public function getCount()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return (string) $this->count;
    }

    /**
     * @param float $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->setChild('count', $count);
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return (string) $this['code'];
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this['code'] = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        if (isset($this->{'price-entry'}['price'])) {
            return (string) $this->{'price-entry'}['price'];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function hasPrice()
    {
        return null !== $this->getPrice();
    }
}
