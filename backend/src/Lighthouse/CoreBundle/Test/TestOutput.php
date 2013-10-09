<?php

namespace Lighthouse\CoreBundle\Test;

use Symfony\Component\Console\Output\Output;

class TestOutput extends Output
{
    /**
     * @var array
     */
    protected $lines = array(0 => '');

    /**
     * @var string
     */
    protected $pos = 0;

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param Boolean $newline Whether to add a newline or not
     */
    protected function doWrite($message, $newline)
    {
        $this->lines[$this->pos].= $message;
        if ($newline) {
            $this->lines[++$this->pos] = '';
        }
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return implode(PHP_EOL, $this->lines);
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }
}
