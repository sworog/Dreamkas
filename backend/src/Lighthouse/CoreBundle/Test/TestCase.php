<?php

namespace Lighthouse\CoreBundle\Test;

use PHPUnit_Framework_TestCase;
use Exception;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @param Exception $e
     */
    protected function onNotSuccessfulTest(Exception $e)
    {
        $e = SerializableException::factory($e);
        parent::onNotSuccessfulTest($e);
    }
}
