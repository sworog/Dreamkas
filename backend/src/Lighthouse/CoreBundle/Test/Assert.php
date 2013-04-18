<?php

namespace Lighthouse\CoreBundle\Test;

use Lighthouse\CoreBundle\Test\Constraint\ResponseCode;
use Lighthouse\CoreBundle\Util\JsonPath;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class Assert
{
    /**
     * @param int $expectedCode
     * @param Response|Client|int $actual
     * @param string $message
     */
    public static function assertResponseCode($expectedCode, $actual, $message = '')
    {
        \PHPUnit_Framework_Assert::assertThat(
            $expectedCode,
            new ResponseCode($actual),
            $message
        );
    }

    /**
     * @param string $expected
     * @param string $path
     * @param mixed $json
     * @param string $message
     */
    public static function assertJsonPathEquals($expected, $path, $json, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        $actual = $jsonPath->getValue();
        if ('' == $message) {
            $message = sprintf("Failed asserting JSON path '%s' value '%s' equals '%s'", $path, $expected, $actual);
        }
        \PHPUnit_Framework_Assert::assertEquals($expected, $actual, $message);
    }

    /**
     * @param string $expected
     * @param string $path
     * @param mixed $json
     * @param string $message
     */
    public static function assertJsonPathContains($expected, $path, $json, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        $actual = $jsonPath->getValue();
        if ('' == $message) {
            $message = sprintf("Failed asserting JSON path '%s' value '%s' contains '%s'", $path, $expected, $actual);
        }
        \PHPUnit_Framework_Assert::assertContains($expected, $actual, $message);
    }

    /**
     * @param string $path
     * @param mixed $json
     * @param string $message
     */
    public static function assertJsonHasPath($path, $json, $message = '')
    {
        if ('' == $message) {
            $message = sprintf("JSON path '%s' not found", $path);
        }
        $jsonPath = new JsonPath($json, $path);
        $found = $jsonPath->isFound();
        \PHPUnit_Framework_Assert::assertTrue($found, $message);
    }

    /**
     * @param string $path
     * @param mixed $json
     * @param string $message
     */
    public static function assertNotJsonHasPath($path, $json, $message = '')
    {
        if ('' == $message) {
            $message = sprintf("JSON path '%s' should not be found", $path);
        }
        $jsonPath = new JsonPath($json, $path);
        $found = $jsonPath->isFound();
        \PHPUnit_Framework_Assert::assertFalse($found, $message);
    }
}
