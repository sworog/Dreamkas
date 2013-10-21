<?php

namespace Lighthouse\CoreBundle\Test;

use PHPUnit_Framework_TestCase;
use Exception;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var bool
     */
    protected $isolated = false;

    /**
     * @param bool $inIsolation
     */
    public function setInIsolation($inIsolation)
    {
        parent::setInIsolation($inIsolation);
        $this->isolated = $inIsolation;

    }

    /**
     * @param Exception $e
     */
    protected function onNotSuccessfulTest(Exception $e)
    {
        if ($this->isolated) {
            $e = SerializableException::factory($e);
        }
        parent::onNotSuccessfulTest($e);
    }
}
