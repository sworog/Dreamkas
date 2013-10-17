<?php

namespace Lighthouse\CoreBundle\Meta;

interface MetaGeneratorInterface
{
    /**
     * Return meta array for element
     * @param $element
     * @return array
     */
    public function getMetaForElement($element);
}
