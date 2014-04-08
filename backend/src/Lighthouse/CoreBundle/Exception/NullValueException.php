<?php

namespace Lighthouse\CoreBundle\Exception;

class NullValueException extends InvalidArgumentException
{
    /**
     * Field|param name
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct(sprintf('%s is null', $name));
    }
}
