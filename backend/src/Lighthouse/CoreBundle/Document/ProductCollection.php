<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\MongoDB\Cursor;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\XmlRoot("products")
 */
class ProductCollection
{
    /**
     * @Serializer\SerializedName("products")
     * @Serializer\XmlList(inline = true, entry="product")
     */
    public $elements;

    /**
     * @param array|Cursor $elements
     */
    public function __construct($elements)
    {
        if ($elements instanceof Cursor) {
            $elements = $elements->toArray(false);
        }
        $this->elements = $elements;
    }
}