<?php

namespace Lighthouse\CoreBundle\Util\Rest;

use FOS\RestBundle\Util\ExceptionWrapper as BaseExceptionWrapper;

class ExceptionWrapper extends BaseExceptionWrapper
{
    private $exceptions;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct($data);

        if (isset($data['exceptions'])) {
            $this->exceptions = $data['exceptions'];
        }
    }
}
