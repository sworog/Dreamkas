<?php

namespace Lighthouse\CoreBundle\Test;

use PHPUnit_Framework_TestCase;
use Exception;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var bool
     */
    protected $_inIsolation;

    /**
     * @param bool $inIsolation
     */
    public function setInIsolation($inIsolation)
    {
        parent::setInIsolation($inIsolation);
        $this->_inIsolation = $inIsolation;

    }

    /**
     * @param Exception $e
     */
    protected function onNotSuccessfulTest(Exception $e)
    {
        if ($this->_inIsolation) {
            $e = SerializableException::factory($e);
        }
        parent::onNotSuccessfulTest($e);
    }
}
