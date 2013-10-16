<?php

namespace Lighthouse\CoreBundle\Document\Meta;

interface MetaGeneratorInterface
{
    /**
     * Return meta array for element
     * @param $element
     * @return array
     */
    public function getMetaForElement($element);
}
