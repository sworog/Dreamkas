<?php

namespace Lighthouse\CoreBundle\Debug;

use ErrorException;

class ErrorProxy
{
    /**
     * @var ErrorHandler
     */
    private $handler;

    /**
     * @var object
     */
    private $object;

    /**
     * @param ErrorHandler $handler
     * @param callable $object
     */
    public function __construct(ErrorHandler $handler, $object)
    {
        $this->handler = $handler;
        $this->object = $object;
    }

    /**
     * @param $name
     * @param array $args
     * @return mixed
     * @throws \ErrorException
     * @throws \Exception
     */
    public function __call($name, array $args)
    {
        try {
            $result = call_user_func_array(array($this->object, $name), $args);
        } catch (ErrorException $e) {
            $this->handler->restore();
            throw $e;
        }
        $this->handler->restore();
        return $result;
    }
}
