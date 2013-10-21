<?php

namespace Lighthouse\CoreBundle\Test;

use Exception;

class SerializableException extends Exception
{
    /**
     * @var \Exception
     */
    protected $previous;

    /**
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        parent::__construct((string) $e, $e->getCode(), $e);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array('message', 'code');
    }

    /**
     * @param Exception $e
     * @return Exception|SerializableException
     */
    public static function factory(Exception $e)
    {
        try {
            serialize($e);
        } catch (Exception $serializeException) {
            $e = new static($e);
        }
        return $e;
    }
}
