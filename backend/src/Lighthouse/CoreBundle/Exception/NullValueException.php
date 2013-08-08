<?php

namespace Lighthouse\CoreBundle\Exception;

use InvalidArgumentException;

class NullValueException extends InvalidArgumentException implements LighthouseExceptionInterface
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
