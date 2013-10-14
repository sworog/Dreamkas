<?php

namespace Lighthouse\CoreBundle\Util;

use Lighthouse\CoreBundle\Exception\RuntimeException;

class Url
{
    const SCHEME = 'scheme';
    const HOST = 'host';
    const PORT = 'port';
    const USER = 'user';
    const PASS = 'pass';
    const PATH = 'path';
    const QUERY = 'query';
    const FRAGMENT = 'fragment';

    /**
     * @var array
     */
    protected $parts = array(
        self::SCHEME    => null,
        self::HOST      => null,
        self::PORT      => null,
        self::USER      => null,
        self::PASS      => null,
        self::PATH      => null,
        self::QUERY     => null,
        self::FRAGMENT  => null
    );

    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        if ($url) {
            $this->parse($url);
        }
    }

    /**
     * @param string $part
     * @throws RuntimeException
     */
    protected function validatePart($part)
    {
        if (!array_key_exists($part, $this->parts)) {
            throw new RuntimeException("Invalid url part '$part'");
        }
    }

    /**
     * @param string $part
     * @return string
     * @throws RuntimeException
     */
    public function getPart($part)
    {
        $this->validatePart($part);
        return $this->parts[$part];
    }

    /**
     * @param string $part
     * @param string $value
     * @throws RuntimeException
     */
    public function setPart($part, $value)
    {
        $this->validatePart($part);
        $this->parts[$part] = $value;
    }

    /**
     * @param $part
     * @return bool
     * @throws RuntimeException
     */
    public function issetPart($part)
    {
        $this->validatePart($part);
        return null !== $this->parts[$part];
    }

    /**
     * @param string $part
     */
    public function unsetPart($part)
    {
        $this->validatePart($part);
        $this->parts[$part] = null;
    }

    /**
     * @param string $url
     * @throws RuntimeException
     */
    protected function parse($url)
    {
        $parts = parse_url($url);
        if (false === $parts) {
            throw new RuntimeException(sprintf('Failed to parse url "%s"', $url));
        }
        foreach ($parts as $part => $value) {
            $this->setPart($part, $value);
        }
    }

    /**
     * @param bool $notEmpty
     * @return array
     */
    public function getParts($notEmpty = false)
    {
        $parts = $this->parts;
        if ($notEmpty) {
            $parts = array_filter($parts, function ($value) {
                return '' !== (string) $value;
            });
        }
        return $parts;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $url = '';
        if (isset($this->parts[self::SCHEME])) {
            $url.= $this->parts[self::SCHEME] . ':';
        }
        $url.= '//';
        if ($this->issetPart(self::USER)) {
            $url.= $this->getPart(self::USER);
            if ($this->issetPart(self::PASS)) {
                $url.= ':' . $this->getPart(self::PASS);
            }
            $url.= '@';
        }
        if ($this->issetPart(self::HOST)) {
            $url.= $this->getPart(self::HOST);
        }
        if ($this->issetPart(self::PATH)) {
            $url.= '/' . ltrim($this->getPart(self::PATH), '/');
        }
        if ($this->issetPart(self::QUERY)) {
            $url.= '?' . $this->getPart(self::QUERY);
        }
        if ($this->issetPart(self::FRAGMENT)) {
            $url.= '#' . $this->getPart(self::FRAGMENT);
        }
        return $url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
