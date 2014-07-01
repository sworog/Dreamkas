<?php

namespace Lighthouse\CoreBundle\Document;

interface ClassNameable
{
    /**
     * @return string
     */
    public static function getClassName();
}
