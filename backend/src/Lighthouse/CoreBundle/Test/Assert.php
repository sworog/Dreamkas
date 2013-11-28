<?php

namespace Lighthouse\CoreBundle\Test;

use Lighthouse\CoreBundle\Test\Constraint\ResponseCode;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit_Framework_Assert;
use DomainException;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Util_Type;

class Assert
{
    /**
     * @param int $expectedCode
     * @param Response|Client|int $actual
     * @param string $message
     */
    public static function assertResponseCode($expectedCode, $actual, $message = '')
    {
        PHPUnit_Framework_Assert::assertThat(
            $expectedCode,
            new ResponseCode($actual),
            $message
        );
    }

    /**
     * @param string $expected
     * @param string $path
     * @param mixed $json
     * @param bool|int $count
     * @param string $message
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public static function assertJsonPathEquals($expected, $path, $json, $count = true, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        try {
            $values = $jsonPath->getValues(true);
        } catch (DomainException $e) {
            throw new PHPUnit_Framework_ExpectationFailedException($e->getMessage(), null, $e);
        }
        $found = 0;
        $notFoundValues = array();
        foreach ($values as $value) {
            try {
                PHPUnit_Framework_Assert::assertEquals($expected, $value, $message);
                $found++;
            } catch (PHPUnit_Framework_ExpectationFailedException $e) {
                $notFoundValues[] = PHPUnit_Util_Type::export($value);
            }
        }

        if (true === $count) {
            $result = $found > 0;
        } elseif (false === $count) {
            $result = $found == 0;
        } else {
            $result = $found == $count;
        }

        if ('' == $message) {
            $message = sprintf(
                "Failed asserting JSON path '%s' value equals '%s'.\nFound values: %s",
                $path,
                $expected,
                implode(', ', $notFoundValues)
            );
        }

        PHPUnit_Framework_Assert::assertTrue($result, $message);
    }

    /**
     * @param $expected
     * @param $path
     * @param $json
     * @param string $message
     */
    public static function assertNotJsonPathEquals($expected, $path, $json, $message = '')
    {
        static::assertJsonPathEquals($expected, $path, $json, false, $message);
    }

    /**
     * @param $expected
     * @param $path
     * @param $json
     * @param bool $canonicalize
     * @param string $message
     */
    public static function assertJsonPathEqualsArray($expected, $path, $json, $canonicalize = true, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        $values = $jsonPath->getValues();
        PHPUnit_Framework_Assert::assertEquals($expected, $values, $message, 0, 10, $canonicalize);
    }

    /**
     * @param string $expected
     * @param string $path
     * @param mixed $json
     * @param bool|int $count
     * @param string $message
     */
    public static function assertJsonPathContains($expected, $path, $json, $count = true, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        $values = $jsonPath->getValues();

        $found = 0;
        $notFoundValues = array();
        foreach ($values as $value) {
            try {
                \PHPUnit_Framework_Assert::assertContains($expected, $value, $message);
                $found++;
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $notFoundValues[] = \PHPUnit_Util_Type::export($value);
            }
        }

        if (true === $count) {
            $result = $found > 0;
        } elseif (false === $count) {
            $result = $found == 0;
        } else {
            $result = $found == $count;
        }

        if ('' == $message) {
            $message = sprintf(
                "Failed asserting JSON path '%s' value contains '%s'.\nFound values: %s",
                $path,
                $expected,
                implode(', ', $notFoundValues)
            );
        }

        PHPUnit_Framework_Assert::assertTrue($result, $message);
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
        PHPUnit_Framework_Assert::assertTrue($found, $message);
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
        PHPUnit_Framework_Assert::assertFalse($found, $message);
    }

    /**
     * @param int $expected
     * @param string $path
     * @param array $json
     * @param string $message
     */
    public static function assertJsonPathCount($expected, $path, $json, $message = '')
    {
        $jsonPath = new JsonPath($json, $path);
        $actual = $jsonPath->count();

        if ('' == $message) {
            $message = sprintf("JSON path '%s' actual size %d matches expected size %d", $path, $actual, $expected);
        }

        PHPUnit_Framework_Assert::assertEquals($expected, $actual, $message);
    }

    /**
     * @param string $expectedBefore
     * @param string $expectedLater
     * @param string $haystack
     * @param string $message
     */
    public static function assertStringOccursBefore($expectedBefore, $expectedLater, $haystack, $message = '')
    {
        $message = ($message) ?: sprintf('Failed asserting %s occurs before %s', $expectedBefore, $expectedLater);
        $beforePosition = strpos($haystack, $expectedBefore);
        $laterPosition = strpos($haystack, $expectedLater);
        PHPUnit_Framework_Assert::assertLessThan($laterPosition, $beforePosition, $message);
    }
}
