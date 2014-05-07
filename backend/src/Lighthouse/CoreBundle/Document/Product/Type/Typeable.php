<?php

namespace Lighthouse\CoreBundle\Document\Product\Type;

interface Typeable
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getUnits();
}
