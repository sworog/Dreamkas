<?php

namespace Lighthouse\CoreBundle\Util;

class JsonPath
{
    const DELIMITER = '.';
    
    /**
     * @var mixed|array
     */
    protected $json;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param mixed $json
     * @param string $path
     */
    public function __construct($json, $path)
    {
        $this->json = $json;
        $this->path = $path;
    }

    /**
     * @return array|mixed
     */
    public function getValues()
    {
        return $this->path($this->json, $this->path, true);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $values = $this->getValues();
        $value = reset($values);
        return $value;
    }

    /**
     * @return array|mixed
     * @throws \DomainException
     */
    protected function path(array $array, $path, $first = false)
    {
        $delimiter = self::DELIMITER;
        // Remove starting delimiters and spaces
        $path = ltrim($path, "{$delimiter} ");

        // Remove ending delimiters, spaces, and wildcards
        $path = rtrim($path, "{$delimiter} *");

        // Split the keys by delimiter
        $keys = explode($delimiter, $path);

        do {
            $key = array_shift($keys);

            if (ctype_digit($key)) {
                // Make the key an integer
                $key = (int)$key;
            }

            if (isset($array[$key])) {
                if ($keys) {
                    if (is_array($array[$key])) {
                        // Dig down into the next part of the path
                        $array = $array[$key];
                    } else {
                        // Unable to dig deeper
                        break;
                    }
                } else {
                    // Found the path requested
                    if ($first) {
                        return array($array[$key]);
                    } else {
                        return $array[$key];
                    }
                }
            } elseif ($key === '*') {
                // Handle wildcards

                $values = array();
                foreach ($array as $arr) {
                    if ($value = $this->path($arr, implode($delimiter, $keys))) {
                        $values[] = $value;
                    }
                }

                if ($values) {
                    // Found the values requested
                    return $values;
                } else {
                    // Unable to dig deeper
                    break;
                }
            } else {
                // Unable to dig deeper
                break;
            }
        } while ($keys);

        throw new \DomainException("Json path " .$this->path. " not found");
    }

    /**
     * @return bool
     */
    public function isFound()
    {
        try {
            $this->getValues();
            return true;
        } catch (\DomainException $e) {
            return false;
        }
    }

    /**
     * return int
     */
    public function count()
    {
        try {
            $value = $this->getValues();
            if (is_array($value)) {
                $count = count($value);
            } else {
                $count = 1;
            }
        } catch (\DomainException $e) {
            $count = 0;
        }

        return $count;
    }
}
