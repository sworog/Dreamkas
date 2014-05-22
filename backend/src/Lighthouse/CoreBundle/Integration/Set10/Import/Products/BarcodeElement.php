<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Lighthouse\CoreBundle\Integration\Set10\SimpleXMLElement;

/**
 * @method static BarcodeElement createBySimpleXml()
 */
class BarcodeElement extends SimpleXMLElement
{
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
     * @return string
     */
    public function getCount()
    {
        return (string) $this->count;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return (string) $this['code'];
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
