<?php

namespace Lighthouse\CoreBundle\Tests\Debug;

use Lighthouse\CoreBundle\Debug\ErrorHandler;
use Lighthouse\CoreBundle\Test\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function testRestoreOnlyOnce()
    {
        $handler = ErrorHandler::register();
        $this->assertTrue($handler->restore());
        $this->assertFalse($handler->restore());
    }

    public function testUserWarning()
    {
        $handler = ErrorHandler::register();
        try {
            trigger_error('warning', E_USER_WARNING);
            $handler->restore();
            $this->assertTrue(false);
        } catch (\ErrorException $e) {
            $handler->restore();
            $this->assertEquals(E_USER_WARNING, $e->getSeverity());
            $this->assertEquals('warning', $e->getMessage());
        }
    }

    public function testLevel0WithPreviousHandler()
    {
        set_error_handler(function () {
            throw new \RuntimeException('Other handler');
        });
        $handler = ErrorHandler::register(0);
        try {
            trigger_error('warning', E_USER_WARNING);
            $this->assertTrue(false);
        } catch (\RuntimeException $e) {
            $this->assertEquals('Other handler', $e->getMessage());
        }
        $handler->restore();
    }

    public function testLevel0WithoutPreviousHandler()
    {
        $previous = set_error_handler(function () {
            throw new \RuntimeException('Other handler');
        });
        restore_error_handler();
        restore_error_handler();

        $handler = ErrorHandler::register(0);
        /* @var bool|\SimpleXMLElement $result */
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        $result = @simplexml_load_string('dummy');
        $this->assertFalse($result);
        $handler->restore();

        if ($previous) {
            set_error_handler($previous);
        }
    }

    /**
     * @expectedException \ErrorException
     * @expectedExceptionMessage XMLReader::expand(): Load Data before trying to expand
     */
    public function testProxyWarning()
    {
        $object = new \XMLReader();
        ErrorHandler::proxy($object)->expand();
    }

    /**
     */
    public function testProxyNoError()
    {
        $object = new \XMLReader();
        $result = ErrorHandler::proxy($object)->close();
        $this->assertTrue($result);
    }


    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Lstat failed for unknown
     */
    public function testProxyObjectException()
    {
        $object = new \SplFileInfo('unknown');
        ErrorHandler::proxy($object)->getType();
    }
}
