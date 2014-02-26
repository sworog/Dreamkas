<?php

namespace Lighthouse\CoreBundle\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;

class BaseType extends Type
{
    /**
     * @param $value
     * @return mixed
     */
    public static function convertToMongo($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function convertToPhp($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    final public function convertToDatabaseValue($value)
    {
        return static::convertToMongo($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    final public function convertToPHPValue($value)
    {
        return static::convertToPhp($value);
    }

    /**
     * @return string
     */
    final public function closureToMongo()
    {
        return '$return = \\' . static::getClassName() . '::convertToMongo($value);';
    }

    /**
     * @return string
     */
    final public function closureToPHP()
    {
        return '$return = \\' . static::getClassName() . '::convertToPhp($value);';
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}
