<?php

namespace Lighthouse\CoreBundle\Util;

class JsonPath
{
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
     * @throws \DomainException
     */
    public function getValue()
    {
        $path = trim($this->path, '.');
        $sections = explode('.', $path);
        $node = $this->json;
        foreach ($sections as $section) {
            if (is_array($node)) {
                if (array_key_exists($section, $node)) {
                    $node = $node[$section];
                } else {
                    throw new \DomainException(sprintf("Path '%s' not found in JSON", $path));
                }
            } elseif ($node instanceof \stdClass) {
                if (property_exists($node, $section)) {
                    $node = $node->$section;
                } else {
                    throw new \DomainException(sprintf("Path '%s' not found in JSON", $path));
                }
            }
        }
        return $node;
    }

    /**
     * @return bool
     */
    public function isFound()
    {
        try {
            $this->getValue();
            return true;
        } catch (\DomainException $e) {
            return false;
        }
    }
}
