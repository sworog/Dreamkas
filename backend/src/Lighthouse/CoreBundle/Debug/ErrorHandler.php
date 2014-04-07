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
        set_error_handler(array($handler, 'handle'));

        return $handler;
    }

    public function restore()
    {
        restore_error_handler();
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
