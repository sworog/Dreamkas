<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractRounding
{
    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("name")
     *
     * @return string
     */
    abstract public function getName();

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("title")
     *
     * @return string
     */
    public function getTitle()
    {
        return 'lighthouse.rounding.' . $this->getName();
    }

    abstract public function round($value);
}
