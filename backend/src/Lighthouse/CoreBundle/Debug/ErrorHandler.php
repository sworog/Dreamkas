<?php

namespace Lighthouse\CoreBundle\Debug;

use ErrorException;

class ErrorHandler
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var callable
     */
    private $previous;

    /**
     * @param null $level
     */
    private function __construct($level = null)
    {
        $this->setLevel($level);
    }

    /**
     * @param int|null $level
     */
    private function setLevel($level = null)
    {
        $this->level = (null === $level) ? error_reporting() : $level;
    }

    /**
     * @param int|null $level
     * @return ErrorHandler
     */
    public static function register($level = null)
    {
        $handler = new self($level);

        ini_set('display_errors', 0);
        $previous = set_error_handler(array($handler, 'handle'));

        $handler->previous = $previous;

        return $handler;
    }

    /**
     * @return bool
     */
    public function restore()
    {
        $previous = set_error_handler(function() {
        });
        restore_error_handler();
        if (is_array($previous) && $previous[0] === $this && 'handle' === $previous[1]) {
            restore_error_handler();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @throws \ErrorException
     * @return bool
     */
    public function handle($level, $message, $file, $line)
    {
        if (0 !== $this->level && error_reporting() & $level && $this->level & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        } elseif (null !== $this->previous) {
            return call_user_func($this->previous, $level, $message, $file, $line);
        } else {
            return false;
        }
    }

    /**
     * @param object $object
     * @param null $level
     * @return ErrorProxy|\XmlReader|\SplFileInfo
     */
    public static function proxy($object, $level = null)
    {
        $handler = static::register($level);
        $proxy = new ErrorProxy($handler, $object);
        return $proxy;
    }
}
