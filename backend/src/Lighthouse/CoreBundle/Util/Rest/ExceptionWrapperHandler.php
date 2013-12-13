<?php

namespace Lighthouse\CoreBundle\Util\Rest;

use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;

class ExceptionWrapperHandler implements ExceptionWrapperHandlerInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function wrap($data)
    {
        return new ExceptionWrapper($data);
    }
}
